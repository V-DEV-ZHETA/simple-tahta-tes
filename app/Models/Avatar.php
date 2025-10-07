<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    protected $fillable = [
    'name',
    'email',
    'username',
    'telepon',
    'avatar',  // Tambahkan ini
    'password',
    'verified_at',
];
}
