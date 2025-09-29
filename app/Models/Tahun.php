<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tahun extends Model
{
    protected $fillable = [
        'year',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
