<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TradingSymbol extends Model
{
    use SoftDeletes;

    /**
     * Explicit table name
     * (Model name ≠ table name is perfectly valid)
     */
    protected $table = 'symbols';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'symbol_code',
        'symbol_name',
        'instrument_category', // equity, index, commodity
        'instrument_type',     // stock, option, future
        'exchange',            // NSE, MCX
        'lot_size',
        'tick_size',
        'is_tradable',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'is_tradable' => 'boolean',
        'is_active'   => 'boolean',
    ];

    /* -------------------------------------------------
     | Relationships
     |--------------------------------------------------
     */

    /**
     * One symbol → many option contracts
     */
    public function optionContracts(): HasMany
    {
        return $this->hasMany(OptionContract::class, 'symbol_id');
    }

    /**
     * One symbol → many daily trend analyses
     */
    public function dailyTrendAnalyses(): HasMany
    {
        return $this->hasMany(DailyTrendAnalysis::class, 'symbol_id');
    }

    /**
     * One symbol → many daily trade plans
     */
    public function dailyTradePlans(): HasMany
    {
        return $this->hasMany(DailyTradePlan::class, 'symbol_id');
    }
}
