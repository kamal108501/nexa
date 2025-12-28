<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TradingSymbol extends Model
{
    use SoftDeletes;

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

    public function optionContracts(): HasMany
    {
        return $this->hasMany(OptionContract::class);
    }

    public function dailyTrendLogs(): HasMany
    {
        return $this->hasMany(DailyTrendLog::class);
    }

    public function dailyTradePlans(): HasMany
    {
        return $this->hasMany(DailyTradePlan::class);
    }

    public function stockTips(): HasMany
    {
        return $this->hasMany(StockTip::class);
    }
}
