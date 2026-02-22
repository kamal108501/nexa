<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MalaJapaLog extends Model
{
    protected $fillable = [
        'japa_date',
    ];

    protected $casts = [
        'japa_date' => 'date',
    ];
}
