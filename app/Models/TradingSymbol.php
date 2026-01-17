<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TradingSymbol extends Model
{
    use SoftDeletes;

    protected $table = 'trading_symbols';

    public $timestamps = true;

    protected $fillable = [
        'symbol_code',
        'name',
        'exchange',
        'segment',
        'lot_size',
        'tick_size',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'lot_size'    => 'integer',
        'tick_size'   => 'decimal:4',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    /* ================= Relationships ================= */

    public function optionContracts(): HasMany
    {
        return $this->hasMany(OptionContract::class, 'trading_symbol_id');
    }

    public function dailyTrendLogs(): HasMany
    {
        return $this->hasMany(DailyTrendLog::class, 'trading_symbol_id');
    }

    public function dailyTradePlans(): HasMany
    {
        return $this->hasMany(DailyTradePlan::class, 'trading_symbol_id');
    }

    public function stockTips(): HasMany
    {
        return $this->hasMany(StockTip::class, 'trading_symbol_id');
    }

    /* ================= Scopes ================= */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSegment($query, string $segment)
    {
        return $query->where('segment', $segment);
    }
}
