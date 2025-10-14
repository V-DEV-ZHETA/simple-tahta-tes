<?php

namespace App\Models;

use App\Enums\JalurPelatihan;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Illuminate\Database\Eloquent\Model;

class BentukPelatihan extends Model
{
    protected $table = 'bentuk_pelatihans';

    protected $fillable = ['jalur', 'bentuk', 'deskripsi'];

    protected $casts = [
        'jalur' => JalurPelatihan::class,
    ];
}
