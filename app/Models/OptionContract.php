<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OptionContract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'symbol_id',
        'expiry_date',
        'strike_price',
        'option_type',
        'lot_size',
        'is_weekly',
        'is_active',
        'contract_code',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_weekly' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function symbol(): BelongsTo
    {
        return $this->belongsTo(TradingSymbol::class, 'symbol_id');
    }

    public function dailyTradePlans(): HasMany
    {
        return $this->hasMany(DailyTradePlan::class);
    }
}
