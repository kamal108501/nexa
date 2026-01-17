<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTradeExecution extends Model
{
    use SoftDeletes;

    protected $table = 'stock_trade_executions';

    protected $fillable = [
        'stock_tip_id',
        'trading_symbol_id',
        'execution_type',
        'quantity',
        'price',
        'execution_at',
        'execution_notes',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'execution_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function symbol()
    {
        return $this->belongsTo(TradingSymbol::class, 'trading_symbol_id');
    }

    public function stockTip()
    {
        return $this->belongsTo(StockTip::class);
    }
}
