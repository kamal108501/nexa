<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradeExecution extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'daily_trade_plan_id',
        'execution_status',
        'entry_price',
        'entry_time',
        'exit_price',
        'exit_time',
        'exit_reason',
        'executed_quantity',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time'  => 'datetime',
    ];

    /* ---------------- Relationships ---------------- */

    public function tradePlan()
    {
        return $this->belongsTo(DailyTradePlan::class, 'daily_trade_plan_id');
    }

    public function result()
    {
        return $this->hasOne(TradeResult::class);
    }
}
