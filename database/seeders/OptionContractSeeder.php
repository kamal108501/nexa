<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OptionContractSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch active INDEX symbols only
        $symbols = DB::table('trading_symbols')
            ->where('segment', 'INDEX')
            ->where('is_active', 1)
            ->get();

        if ($symbols->isEmpty()) {
            return;
        }

        // Nearest weekly expiry (Thursday)
        $expiryDate = Carbon::now()->next(Carbon::THURSDAY);

        foreach ($symbols as $symbol) {

            // -------------------------
            // ATM price based on current market
            // -------------------------
            $atmPrice = match ($symbol->symbol_code) {
                'NIFTY'     => 26050,
                'BANKNIFTY' => 59000,
                'SENSEX'    => 85200,
                default     => null,
            };

            if (!$atmPrice) {
                continue;
            }

            // -------------------------
            // Strike gap
            // -------------------------
            $strikeGap = match ($symbol->symbol_code) {
                'NIFTY'     => 50,
                'BANKNIFTY' => 100,
                'SENSEX'    => 100,
                default     => 50,
            };

            // Generate ATM ± 2 strikes
            $strikes = [
                $atmPrice - (2 * $strikeGap),
                $atmPrice - $strikeGap,
                $atmPrice,
                $atmPrice + $strikeGap,
                $atmPrice + (2 * $strikeGap),
            ];

            foreach ($strikes as $strike) {
                foreach (['CE', 'PE'] as $optionType) {

                    // NSE-style contract code
                    $contractCode = sprintf(
                        '%s%s%s%s',
                        $symbol->symbol_code,
                        $expiryDate->format('yMd'), // e.g. 25Jan02
                        $strike,
                        $optionType
                    );

                    DB::table('option_contracts')->updateOrInsert(
                        [
                            'symbol_id'    => $symbol->id,
                            'expiry_date'  => $expiryDate->toDateString(),
                            'strike_price' => $strike,
                            'option_type'  => $optionType,
                        ],
                        [
                            'contract_code' => $contractCode,
                            'lot_size'      => $symbol->lot_size,
                            'tick_size'     => 0.05,
                            'is_weekly'     => 1,
                            'is_active'     => 1,
                            'created_by'    => 1,
                            'updated_by'    => 1,
                            'created_at'    => Carbon::now(),
                            'updated_at'    => Carbon::now(),
                        ]
                    );
                }
            }
        }
    }
}
