<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradeResult extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'daily_trade_plan_id',
        'trade_execution_id',
        'entry_price',
        'exit_price',
        'quantity',
        'invested_amount',
        'gross_pnl',
        'pnl_percentage',
        'r_multiple',
        'is_profitable',
        'result_type',
        'brokerage',
        'taxes',
        'net_pnl',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_profitable' => 'boolean',
    ];

    /* ---------------- Relationships ---------------- */

    public function tradePlan()
    {
        return $this->belongsTo(DailyTradePlan::class, 'daily_trade_plan_id');
    }

    public function execution()
    {
        return $this->belongsTo(TradeExecution::class, 'trade_execution_id');
    }
}
