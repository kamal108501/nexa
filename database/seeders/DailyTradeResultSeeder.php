<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyTradeResultSeeder extends Seeder
{
    public function run(): void
    {
        $plans = DB::table('daily_trade_plans as dtp')
            ->join('option_contracts as oc', 'oc.id', '=', 'dtp.option_contract_id')
            ->whereIn('dtp.status', ['planned', 'executed'])
            ->whereNull('dtp.deleted_at')
            ->select(
                'dtp.id as plan_id',
                'dtp.trade_date',
                'dtp.planned_entry_price',
                'dtp.quantity as lots',
                'oc.lot_size'
            )
            ->get();

        if ($plans->isEmpty()) {
            return;
        }

        foreach ($plans as $plan) {

            // Skip if result already exists
            $exists = DB::table('daily_trade_results')
                ->where('daily_trade_plan_id', $plan->plan_id)
                ->exists();

            if ($exists) {
                continue;
            }

            // -----------------------------
            // ENTRY
            // -----------------------------
            $entryPrice = round(
                $plan->planned_entry_price + rand(-2, 2),
                2
            );

            // Decide profit or loss (70% profit)
            $isProfit = rand(1, 100) <= 70;

            // -----------------------------
            // EXIT
            // -----------------------------
            if ($isProfit) {
                $exitPrice = round($entryPrice * rand(120, 160) / 100, 2);
            } else {
                $exitPrice = round($entryPrice * rand(60, 90) / 100, 2);
            }

            // -----------------------------
            // CALCULATIONS
            // -----------------------------
            $points = round($exitPrice - $entryPrice, 2);

            $units = $plan->lots * $plan->lot_size;

            $pnlAmount = round($points * $units, 2);

            $investedAmount = $entryPrice * $units;

            $pnlPercent = round(($pnlAmount / $investedAmount) * 100, 2);

            $result = $pnlAmount >= 0 ? 'profit' : 'loss';

            // -----------------------------
            // TIME
            // -----------------------------
            $entryTime = Carbon::createFromTime(9, rand(20, 45));
            $exitTime  = (clone $entryTime)->addMinutes(rand(30, 180));

            DB::table('daily_trade_results')->insert([
                'daily_trade_plan_id' => $plan->plan_id,
                'trade_date'          => $plan->trade_date,
                'entry_price'         => $entryPrice,
                'entry_time'          => $entryTime->format('Y-m-d H:i:s'),
                'exit_price'          => $exitPrice,
                'exit_time'           => $exitTime->format('Y-m-d H:i:s'),
                'points'              => $points,
                'pnl_amount'          => $pnlAmount,
                'pnl_percent'         => $pnlPercent,
                'result'              => $result,
                'created_by'          => 1,
                'updated_by'          => 1,
                'created_at'          => Carbon::now(),
                'updated_at'          => Carbon::now(),
            ]);
        }
    }
}
