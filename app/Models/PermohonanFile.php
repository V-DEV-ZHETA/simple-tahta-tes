<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanFile extends Model
{
    protected $fillable = ['bangkom_id', 'file_path'];

    public function bangkom()
    {
        return $this->belongsTo(Bangkom::class);
    }
}
