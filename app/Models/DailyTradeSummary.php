<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyTradeSummary extends Model
{
    use SoftDeletes;

    protected $table = 'daily_trade_summaries';

    protected $fillable = [
        'trade_date',
        'segment',

        'total_trades',
        'winning_trades',
        'losing_trades',

        'gross_profit',
        'gross_loss',
        'net_pl',

        'capital_used',
        'roi_percent',

        'discipline_score',
        'remark',

        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'trade_date' => 'date',

        'total_trades'   => 'integer',
        'winning_trades' => 'integer',
        'losing_trades'  => 'integer',

        'gross_profit' => 'decimal:2',
        'gross_loss'   => 'decimal:2',
        'net_pl'       => 'decimal:2',

        'capital_used' => 'decimal:2',
        'roi_percent'  => 'decimal:2',

        'discipline_score' => 'integer',
        'is_active'        => 'boolean',
    ];

    /**
     * Scope: only active summaries
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Helper: check if day was profitable
     */
    public function getIsProfitableAttribute(): bool
    {
        return $this->net_pl > 0;
    }
}
