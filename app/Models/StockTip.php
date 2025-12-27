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
        'symbol_id',
        'tip_date',
        'buy_price',
        'stop_loss',
        'target_price',
        'holding_days',
        'expiry_date',
        'expected_return_percent',
        'status',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tip_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function symbol(): BelongsTo
    {
        return $this->belongsTo(TradingSymbol::class, 'symbol_id');
    }

    public function result(): HasOne
    {
        return $this->hasOne(StockTipResult::class);
    }
}
