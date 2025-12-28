<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyTradePlanSeeder extends Seeder
{
    public function run(): void
    {
        $tradeDate = Carbon::today();

        // Fetch ATM weekly option contracts for INDEX symbols
        $contracts = DB::table('option_contracts as oc')
            ->join('trading_symbols as ts', 'ts.id', '=', 'oc.symbol_id')
            ->where('ts.segment', 'INDEX')
            ->where('oc.is_weekly', 1)
            ->where('oc.is_active', 1)
            ->whereDate('oc.expiry_date', '>=', $tradeDate)
            ->select(
                'oc.id as option_contract_id',
                'oc.symbol_id',
                'oc.strike_price',
                'oc.option_type',
                'oc.lot_size',
                'ts.symbol_code'
            )
            ->get();

        if ($contracts->isEmpty()) {
            return;
        }

        foreach ($contracts as $contract) {

            // -----------------------------
            // Realistic option premium (approx)
            // -----------------------------
            $basePremium = match ($contract->symbol_code) {
                'NIFTY'     => rand(180, 260),
                'BANKNIFTY' => rand(280, 420),
                'SENSEX'    => rand(300, 450),
                default     => rand(100, 200),
            };

            // -----------------------------
            // Trade plan pricing
            // -----------------------------
            $entryPrice  = $basePremium + rand(2, 5);
            $stopLoss    = $entryPrice * 0.75;      // ~25% SL
            $targetPrice = $entryPrice * 1.5;       // ~50% target

            $quantity = 1;

            $expectedProfit = ($targetPrice - $entryPrice) * $quantity;
            $expectedLoss   = ($entryPrice - $stopLoss) * $quantity;
            $expectedReturn = ($expectedProfit / ($entryPrice * $quantity)) * 100;

            DB::table('daily_trade_plans')->updateOrInsert(
                [
                    'trade_date'        => $tradeDate,
                    'option_contract_id' => $contract->option_contract_id,
                ],
                [
                    'symbol_id'                 => $contract->symbol_id,
                    'current_price'             => round($basePremium, 2),
                    'planned_entry_price'       => round($entryPrice, 2),
                    'stop_loss'                 => round($stopLoss, 2),
                    'target_price'              => round($targetPrice, 2),
                    'quantity'                  => $quantity,
                    'expected_profit'           => round($expectedProfit, 2),
                    'expected_loss'             => round($expectedLoss, 2),
                    'expected_return_percent'   => round($expectedReturn, 2),
                    'status'                    => 'planned',
                    'notes'                     => 'ATM weekly option trade based on momentum',
                    'created_by'                => 1,
                    'updated_by'                => 1,
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),
                ]
            );
        }
    }
}
