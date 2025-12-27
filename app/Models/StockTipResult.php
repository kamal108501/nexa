<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTipResult extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'stock_tip_id',
        'exit_price',
        'exit_date',
        'pnl_amount',
        'pnl_percent',
        'exit_reason',
        'is_correct',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'exit_date' => 'date',
        'is_correct' => 'boolean',
    ];

    public function stockTip(): BelongsTo
    {
        return $this->belongsTo(StockTip::class);
    }
}
