<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyTrendAnalysis extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'symbol_id',
        'analysis_date',
        'trend_type',
        'trend_strength',
        'timeframe',
        'based_on_date',
        'analysis_notes',
        'is_trend_correct',
        'validated_at',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'analysis_date' => 'date',
        'based_on_date' => 'date',
        'validated_at'  => 'datetime',
        'is_trend_correct' => 'boolean',
    ];

    /* ---------------- Relationships ---------------- */

    public function symbol()
    {
        return $this->belongsTo(TradingSymbol::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tradePlans()
    {
        return $this->hasMany(DailyTradePlan::class);
    }
}
