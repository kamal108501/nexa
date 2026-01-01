<?php

namespace App\Services;

use App\Models\TradingMonthlyRiskPlan;
use App\Models\TradingMonthlyRiskStat;
use App\Models\DailyTradeResult;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TradingRiskManager
{
    /**
     * Update monthly risk stats after a trade
     *
     * @param User $user
     * @param float $pnl  // positive = profit, negative = loss
     */
    public function applyTradeResult(User $user, float $pnl): void
    {
        DB::transaction(function () use ($user, $pnl) {

            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;

            // 1️⃣ Get active risk plan
            $plan = TradingMonthlyRiskPlan::where('user_id', $user->id)
                ->where('risk_year', $year)
                ->where('risk_month', $month)
                ->where('is_active', true)
                ->first();

            if (! $plan) {
                throw new \Exception('Monthly risk plan not found.');
            }

            // 2️⃣ Get or create stats row
            $stats = TradingMonthlyRiskStat::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'risk_plan_id' => $plan->id,
                    'risk_year' => $year,
                    'risk_month' => $month,
                ],
                [
                    'current_allowed_loss' => $plan->base_max_loss,
                    'remaining_loss_balance' => $plan->base_max_loss,
                ]
            );

            // 3️⃣ Apply P&L aggregates
            if ($pnl >= 0) {
                $stats->total_profit += $pnl;
            } else {
                $stats->total_loss += abs($pnl);
            }

            // 4️⃣ Recalculate allowed loss (cap) and remaining balance
            $profitForRisk = max(0, $stats->total_profit - $stats->total_loss);
            $extraRisk = ($profitForRisk * $plan->profit_risk_percent) / 100;

            $allowedLossCap = $plan->base_max_loss + $extraRisk; // maximum loss capacity for the month

            // Net loss after offsetting by profits
            $netLoss = max(0, $stats->total_loss - $stats->total_profit);

            $stats->current_allowed_loss = round($allowedLossCap, 2); // display cap (Base / Allowed)

            $stats->remaining_loss_balance = round(
                max(0, $allowedLossCap - $netLoss),
                2
            );

            // 5️⃣ Block trading if breached
            if ($stats->remaining_loss_balance <= 0) {
                $stats->trading_blocked = true;
                $stats->blocked_at = now();
            }

            $stats->save();
        });
    }

    /**
     * Recalculate the current month's risk stats from all trades
     * (useful to fix any drift or manual edits).
     */
    public function recalcMonthlyStatsFromTrades(User $user, ?int $year = null, ?int $month = null): void
    {
        DB::transaction(function () use ($user, $year, $month) {

            $now = Carbon::now();
            $year = $year ?? $now->year;
            $month = $month ?? $now->month;

            // Active plan for the period
            $plan = TradingMonthlyRiskPlan::where('user_id', $user->id)
                ->where('risk_year', $year)
                ->where('risk_month', $month)
                ->where('is_active', true)
                ->first();

            if (! $plan) {
                throw new \Exception('Monthly risk plan not found.');
            }

            // Aggregate P&L for the month from trades (use trade_date if present, else created_at)
            $start = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
            $end = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();

            $totals = DailyTradeResult::query()
                ->whereBetween(DB::raw('DATE(COALESCE(trade_date, created_at))'), [$start, $end])
                ->where(function ($q) use ($user) {
                    $q->where('created_by', $user->id)
                        ->orWhereNull('created_by'); // include legacy rows with no creator
                })
                ->selectRaw('SUM(CASE WHEN pnl_amount > 0 THEN pnl_amount ELSE 0 END) as total_profit')
                ->selectRaw('SUM(CASE WHEN pnl_amount < 0 THEN ABS(pnl_amount) ELSE 0 END) as total_loss')
                ->first();

            $totalProfit = (float) ($totals->total_profit ?? 0);
            $totalLoss = (float) ($totals->total_loss ?? 0);

            $stats = TradingMonthlyRiskStat::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'risk_plan_id' => $plan->id,
                    'risk_year' => $year,
                    'risk_month' => $month,
                ],
                [
                    'current_allowed_loss' => $plan->base_max_loss,
                    'remaining_loss_balance' => $plan->base_max_loss,
                ]
            );

            $stats->total_profit = round($totalProfit, 2);
            $stats->total_loss = round($totalLoss, 2);

            $profitForRisk = max(0, $stats->total_profit - $stats->total_loss);
            $extraRisk = ($profitForRisk * $plan->profit_risk_percent) / 100;

            $allowedLossCap = $plan->base_max_loss + $extraRisk;

            // Net loss after offsetting by profits
            $netLoss = max(0, $stats->total_loss - $stats->total_profit);

            $stats->current_allowed_loss = round($allowedLossCap, 2);
            $stats->remaining_loss_balance = round(
                max(0, $allowedLossCap - $netLoss),
                2
            );

            $stats->trading_blocked = $stats->remaining_loss_balance <= 0;
            $stats->blocked_at = $stats->trading_blocked ? now() : null;

            $stats->save();
        });
    }

    public function createNextMonthRiskPlan(int $userId): ?TradingMonthlyRiskPlan
    {
        return DB::transaction(function () use ($userId) {

            $now = Carbon::now();

            $currentYear = $now->year;
            $currentMonth = $now->month;

            $next = $now->copy()->addMonth();
            $nextYear = $next->year;
            $nextMonth = $next->month;

            // 1️⃣ Current month plan & stats
            $currentPlan = TradingMonthlyRiskPlan::where('user_id', $userId)
                ->where('risk_year', $currentYear)
                ->where('risk_month', $currentMonth)
                ->first();

            $currentStats = TradingMonthlyRiskStat::where('user_id', $userId)
                ->where('risk_year', $currentYear)
                ->where('risk_month', $currentMonth)
                ->first();

            if (! $currentPlan || ! $currentStats) {
                return null;
            }

            // 2️⃣ Prevent duplicate creation
            $alreadyExists = TradingMonthlyRiskPlan::where('user_id', $userId)
                ->where('risk_year', $nextYear)
                ->where('risk_month', $nextMonth)
                ->exists();

            if ($alreadyExists) {
                return null;
            }

            // 3️⃣ Calculate next month base loss
            $profit = $currentStats->total_profit;
            $extraRisk = ($profit * $currentPlan->profit_risk_percent) / 100;

            $nextBaseLoss = $currentPlan->base_max_loss;

            if ($profit > 0 && $currentPlan->carry_profit_to_next_month) {
                $nextBaseLoss += $extraRisk;
            }

            // 4️⃣ Create next month plan
            return TradingMonthlyRiskPlan::create([
                'user_id' => $userId,
                'risk_year' => $nextYear,
                'risk_month' => $nextMonth,
                'base_max_loss' => round($nextBaseLoss, 2),
                'profit_risk_percent' => $currentPlan->profit_risk_percent,
                'carry_profit_to_next_month' => true,
                'is_locked' => false,
                'is_active' => true,
                'created_by' => $userId,
            ]);
        });
    }
}
