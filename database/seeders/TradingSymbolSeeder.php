<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TradingSymbolSeeder extends Seeder
{
    public function run(): void
    {
        $symbols = [
            [
                'symbol_code' => 'NIFTY',
                'name'        => 'Nifty 50',
                'segment'     => 'INDEX',
                'exchange'    => 'NSE',
                'lot_size'    => 75,
                'tick_size'   => 0.05,
            ],
            [
                'symbol_code' => 'BANKNIFTY',
                'name'        => 'Bank Nifty',
                'segment'     => 'INDEX',
                'exchange'    => 'NSE',
                'lot_size'    => 15,
                'tick_size'   => 0.05,
            ],
            [
                'symbol_code' => 'SENSEX',
                'name'        => 'Sensex',
                'segment'     => 'INDEX',
                'exchange'    => 'BSE',
                'lot_size'    => 10,
                'tick_size'   => 0.05,
            ],
            [
                'symbol_code' => 'CRUDEOIL',
                'name'        => 'Crude Oil',
                'segment'     => 'COMMODITY',
                'exchange'    => 'MCX',
                'lot_size'    => 100,
                'tick_size'   => 1,
            ],
            [
                'symbol_code' => 'NATURALGAS',
                'name'        => 'Natural Gas',
                'segment'     => 'COMMODITY',
                'exchange'    => 'MCX',
                'lot_size'    => 1250,
                'tick_size'   => 0.05,
            ],
        ];

        foreach ($symbols as $symbol) {
            DB::table('trading_symbols')->updateOrInsert(
                ['symbol_code' => $symbol['symbol_code']], // unique key
                array_merge($symbol, [
                    'is_active'  => 1,
                    'updated_by' => 1,
                    'created_by' => 1,
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ])
            );
        }
    }
}
