<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyTrendLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'symbol_id',
        'trend_date',
        'predicted_trend',
        'actual_trend',
        'is_prediction_correct',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'trend_date' => 'date',
        'is_prediction_correct' => 'boolean',
    ];

    public function symbol(): BelongsTo
    {
        return $this->belongsTo(TradingSymbol::class, 'symbol_id');
    }
}
