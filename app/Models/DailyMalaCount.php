<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMalaCount extends Model
{
    use HasFactory;

    protected $table = 'daily_mala_counts';
    protected $fillable = [
        'name',
        'start',
        'end',
        'allDay',
        'mala_count',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'allDay' => 'boolean',
    ];
}
