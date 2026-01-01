<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TradingMonthlyRiskPlan extends Model
{
    use SoftDeletes;

    protected $table = 'trading_monthly_risk_plans';

    protected $fillable = [
        'user_id',
        'risk_year',
        'risk_month',
        'base_max_loss',
        'profit_risk_percent',
        'carry_profit_to_next_month',
        'is_locked',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'base_max_loss' => 'decimal:2',
        'profit_risk_percent' => 'decimal:2',
        'carry_profit_to_next_month' => 'boolean',
        'is_locked' => 'boolean',
        'is_active' => 'boolean',
    ];

    /* ==============================
     | Relationships
     |==============================*/

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(
            TradingMonthlyRiskStat::class,
            'risk_plan_id'
        );
    }

    /* ==============================
     | Helpers
     |==============================*/

    public function getMonthLabelAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->risk_month, 1))
            . ' ' . $this->risk_year;
    }
}
