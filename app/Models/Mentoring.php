<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mentoring extends Model
{
    protected $fillable = [
        'nama_mentoring',
        'instansi_id',
        'widyaiswara_id',
        'tanggal_mulai',
        'tanggal_berakhir',
        'kuota',
        'status',
        'deskripsi',
        'persyaratan',
    ];

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    public function widyaiswara(): BelongsTo
    {
        return $this->belongsTo(Widyaiswara::class);
    }
}
