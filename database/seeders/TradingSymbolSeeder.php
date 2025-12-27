<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TradingSymbol;

class TradingSymbolSeeder extends Seeder
{
    public function run(): void
    {
        $symbols = [

            // =======================
            // INDEX
            // =======================
            [
                'symbol_code' => 'NIFTY',
                'name'        => 'NIFTY 50',
                'segment'     => 'INDEX',
                'lot_size'    => 75,
                'tick_size'   => 0.05,
                'is_active'   => true,
            ],
            [
                'symbol_code' => 'BANKNIFTY',
                'name'        => 'BANK NIFTY',
                'segment'     => 'INDEX',
                'lot_size'    => 15,
                'tick_size'   => 0.05,
                'is_active'   => true,
            ],
            [
                'symbol_code' => 'SENSEX',
                'name'        => 'SENSEX',
                'segment'     => 'INDEX',
                'lot_size'    => 10,
                'tick_size'   => 0.05,
                'is_active'   => true,
            ],

            // =======================
            // COMMODITIES (MCX)
            // =======================
            [
                'symbol_code' => 'CRUDEOIL',
                'name'        => 'Crude Oil',
                'segment'     => 'COMMODITY',
                'lot_size'    => 100,
                'tick_size'   => 1,
                'is_active'   => true,
            ],
            [
                'symbol_code' => 'NATURALGAS',
                'name'        => 'Natural Gas',
                'segment'     => 'COMMODITY',
                'lot_size'    => 1250,
                'tick_size'   => 0.05,
                'is_active'   => true,
            ],

            // =======================
            // CRYPTO
            // =======================
            [
                'symbol_code' => 'BTCUSD',
                'name'        => 'Bitcoin / USD',
                'segment'     => 'CRYPTO',
                'lot_size'    => 1,      // treated as 1 unit
                'tick_size'   => 0.01,
                'is_active'   => true,
            ],
            [
                'symbol_code' => 'ETHUSD',
                'name'        => 'Ethereum / USD',
                'segment'     => 'CRYPTO',
                'lot_size'    => 1,
                'tick_size'   => 0.01,
                'is_active'   => true,
            ],
            [
                'symbol_code' => 'XRPUSD',
                'name'        => 'Ripple / USD',
                'segment'     => 'CRYPTO',
                'lot_size'    => 1,
                'tick_size'   => 0.0001,
                'is_active'   => true,
            ],
        ];

        foreach ($symbols as $symbol) {
            TradingSymbol::updateOrCreate(
                ['symbol_code' => $symbol['symbol_code']],
                $symbol
            );
        }
    }
}
