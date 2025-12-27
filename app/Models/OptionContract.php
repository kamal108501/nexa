<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionContract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'symbol_id',
        'expiry_date',
        'strike_price',
        'option_type',
        'lot_size',
        'tick_size',
        'contract_code',
        'is_weekly',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /* ---------------- Relationships ---------------- */

    public function symbol()
    {
        return $this->belongsTo(TradingSymbol::class);
    }

    public function dailyTradePlans()
    {
        return $this->hasMany(DailyTradePlan::class);
    }
}
