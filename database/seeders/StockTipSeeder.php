<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockTipSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch all STOCK symbols dynamically
        $stockSymbols = DB::table('trading_symbols')
            ->where('segment', 'STOCK')
            ->where('is_active', 1)
            ->select('id', 'symbol_code')
            ->get();

        if ($stockSymbols->isEmpty()) {
            return;
        }

        $tipDate = Carbon::today();

        foreach ($stockSymbols as $symbol) {

            // Generate realistic prices based on type
            $basePrice = match ($symbol->symbol_code) {
                'RELIANCE' => 2850,
                'TCS'      => 3900,
                default    => rand(500, 2000),
            };

            $target = $basePrice * 1.04; // ~4% upside
            $stop   = $basePrice * 0.97; // ~3% downside
            $holdingDays = rand(5, 12);

            $expectedReturn = (($target - $basePrice) / $basePrice) * 100;

            DB::table('stock_tips')->updateOrInsert(
                [
                    'symbol_id' => $symbol->id,
                    'tip_date'  => $tipDate,
                ],
                [
                    'buy_price'               => round($basePrice, 2),
                    'stop_loss'               => round($stop, 2),
                    'target_price'            => round($target, 2),
                    'holding_days'            => $holdingDays,
                    'expiry_date'             => $tipDate->copy()->addDays($holdingDays),
                    'expected_return_percent' => round($expectedReturn, 2),
                    'status'                  => 'active',
                    'notes'                   => 'Auto-generated stock tip based on current trend',
                    'created_by'              => 1,
                    'updated_by'              => 1,
                    'created_at'              => Carbon::now(),
                    'updated_at'              => Carbon::now(),
                ]
            );
        }
    }
}
