<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widyaiswara extends Model
{
    protected $fillable = [
        'nip',
        'nama',
        'satker',
        'telpon',
        'email',
        'alamat',
    ];
}
