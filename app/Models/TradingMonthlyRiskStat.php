<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradingMonthlyRiskStat extends Model
{
    use SoftDeletes;

    protected $table = 'trading_monthly_risk_stats';

    protected $fillable = [
        'user_id',
        'risk_plan_id',
        'risk_year',
        'risk_month',
        'total_profit',
        'total_loss',
        'current_allowed_loss',
        'remaining_loss_balance',
        'trading_blocked',
        'blocked_at',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'total_profit' => 'decimal:2',
        'total_loss' => 'decimal:2',
        'current_allowed_loss' => 'decimal:2',
        'remaining_loss_balance' => 'decimal:2',
        'trading_blocked' => 'boolean',
        'blocked_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /* ==============================
     | Relationships
     |==============================*/

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function riskPlan(): BelongsTo
    {
        return $this->belongsTo(
            TradingMonthlyRiskPlan::class,
            'risk_plan_id'
        );
    }

    /* ==============================
     | Business Helpers
     |==============================*/

    public function isTradingAllowed(): bool
    {
        return ! $this->trading_blocked
            && $this->remaining_loss_balance > 0;
    }
}
