<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyTradePlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'trade_date',
        'trade_sequence',
        'daily_trend_analysis_id',
        'symbol_id',
        'option_contract_id',
        'instrument_type',
        'trade_direction',
        'trade_duration',
        'planned_entry_price',
        'planned_stop_loss',
        'planned_target_price',
        'planned_quantity',
        'planned_investment',
        'planned_risk_amount',
        'planned_reward_amount',
        'risk_reward_ratio',
        'plan_status',
        'plan_notes',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'trade_date' => 'date',
    ];

    /* ---------------- Relationships ---------------- */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function symbol()
    {
        return $this->belongsTo(TradingSymbol::class);
    }

    public function optionContract()
    {
        return $this->belongsTo(OptionContract::class);
    }

    public function trendAnalysis()
    {
        return $this->belongsTo(DailyTrendAnalysis::class, 'daily_trend_analysis_id');
    }

    public function execution()
    {
        return $this->hasOne(TradeExecution::class);
    }

    public function result()
    {
        return $this->hasOne(TradeResult::class);
    }
}
