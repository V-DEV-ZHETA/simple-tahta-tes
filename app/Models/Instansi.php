<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'email',
        'website',
    ];
}
