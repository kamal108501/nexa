<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockTradeExecutionSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch active stock tips only
        $stockTips = DB::table('stock_tips')
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->get();

        if ($stockTips->isEmpty()) {
            return;
        }

        foreach ($stockTips as $tip) {

            // -------------------------
            // BUY EXECUTION
            // -------------------------
            $buyQuantity = rand(10, 50); // realistic delivery quantity
            $buyPrice    = $tip->buy_price + rand(-5, 5);

            DB::table('stock_trade_executions')->updateOrInsert(
                [
                    'stock_tip_id'   => $tip->id,
                    'execution_type' => 'buy',
                ],
                [
                    'symbol_id'       => $tip->symbol_id,
                    'quantity'        => $buyQuantity,
                    'price'           => round($buyPrice, 2),
                    'execution_date'  => Carbon::parse($tip->tip_date)->addDay(),
                    'execution_notes' => 'Entry executed near recommended buy price',
                    'is_active'       => 1,
                    'created_by'      => 1,
                    'updated_by'      => 1,
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]
            );

            // -------------------------
            // OPTIONAL SELL EXECUTION
            // (simulate partial / full exit)
            // -------------------------
            if (rand(1, 100) <= 70) { // 70% trades have exit

                $sellQty   = (int) floor($buyQuantity * rand(50, 100) / 100);
                $sellPrice = $tip->target_price - rand(5, 15);

                DB::table('stock_trade_executions')->updateOrInsert(
                    [
                        'stock_tip_id'   => $tip->id,
                        'execution_type' => 'sell',
                    ],
                    [
                        'symbol_id'       => $tip->symbol_id,
                        'quantity'        => $sellQty,
                        'price'           => round($sellPrice, 2),
                        'execution_date'  => Carbon::parse($tip->tip_date)->addDays(rand(3, $tip->holding_days)),
                        'execution_notes' => 'Partial / full profit booking',
                        'is_active'       => 1,
                        'created_by'      => 1,
                        'updated_by'      => 1,
                        'created_at'      => Carbon::now(),
                        'updated_at'      => Carbon::now(),
                    ]
                );
            }
        }
    }
}
