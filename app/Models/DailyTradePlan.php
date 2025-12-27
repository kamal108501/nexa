<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DailyTradePlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'trade_date',
        'symbol_id',
        'option_contract_id',
        'current_price',
        'planned_entry_price',
        'stop_loss',
        'target_price',
        'quantity',
        'expected_profit',
        'expected_loss',
        'expected_return_percent',
        'status',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'trade_date' => 'date',
    ];

    public function symbol(): BelongsTo
    {
        return $this->belongsTo(TradingSymbol::class, 'symbol_id');
    }

    public function optionContract(): BelongsTo
    {
        return $this->belongsTo(OptionContract::class);
    }

    public function result(): HasOne
    {
        return $this->hasOne(DailyTradeResult::class);
    }
}
