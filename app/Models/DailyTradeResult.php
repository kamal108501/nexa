<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyTradeResult extends Model
{
    use SoftDeletes;

    protected $table = 'daily_trade_results';

    protected $fillable = [
        'daily_trade_plan_id',
        'trade_date',
        'entry_time',
        'exit_time',
        'entry_price',
        'exit_price',
        'points',
        'pnl_amount',
        'pnl_percent',
        'result',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function tradePlan(): BelongsTo
    {
        return $this->belongsTo(DailyTradePlan::class, 'daily_trade_plan_id');
    }
}
