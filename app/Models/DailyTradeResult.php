<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyTradeResult extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'daily_trade_plan_id',
        'exit_price',
        'exit_time',
        'pnl_amount',
        'pnl_percent',
        'result',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'exit_time' => 'datetime',
    ];

    public function tradePlan(): BelongsTo
    {
        return $this->belongsTo(DailyTradePlan::class, 'daily_trade_plan_id');
    }
}
