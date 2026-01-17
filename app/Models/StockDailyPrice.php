<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockDailyPrice extends Model
{
    use SoftDeletes;

    protected $table = 'stock_daily_prices';

    protected $fillable = [
        'trading_symbol_id',
        'price_date',
        'open_price',
        'high_price',
        'low_price',
        'close_price',
        'volume',
        'source',
    ];

    protected $casts = [
        'price_date'  => 'date',
        'open_price'  => 'decimal:2',
        'high_price'  => 'decimal:2',
        'low_price'   => 'decimal:2',
        'close_price' => 'decimal:2',
    ];
}
