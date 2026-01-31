<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StockTip extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'trading_symbol_id',
        'tip_date',
        'buy_price',
        'stop_loss',
        'target_price',
        'holding_days',
        'expiry_date',
        'expected_return_percent',
        'status',
        'term',
        'exit_price',
        'exit_date',
        'is_active',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tip_date' => 'date',
        'expiry_date' => 'date',
        'exit_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function symbol(): BelongsTo
    {
        return $this->belongsTo(TradingSymbol::class, 'trading_symbol_id');
    }
}
