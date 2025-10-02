<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Instansi;
use App\Models\JenisPelatihan;
use App\Models\Sasaran;

class Bangkom extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'instansi_id',
        'unit_kerja',
        'nama_kegiatan',
        'kode_kegiatan',
        'jenis_pelatihan_id',
        'bentuk_pelatihan_id',
        'sasaran_id',
        'tanggal_mulai',
        'tanggal_berakhir',
        'tempat',
        'alamat',
        'kuota',
        'nama_panitia',
        'telepon_panitia',
        'status',
        'kurikulum',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'kuota' => 'integer',
        'kurikulum' => 'array',
    ];







    // Relasi dengan model lain
    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function jenisPelatihan()
    {
        return $this->belongsTo(JenisPelatihan::class);
    }

    public function bentukPelatihan()
    {
        return $this->belongsTo(\App\Models\BentukPelatihan::class);
    }

    public function sasaran()
    {
        return $this->belongsTo(Sasaran::class);
    }

    public function permohonanFiles()
    {
        return $this->hasMany(PermohonanFile::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(StatusHistory::class);
    }

    protected static function booted()
    {
        static::updating(function ($bangkom) {
            if ($bangkom->isDirty('status')) {
                $oldStatus = $bangkom->getOriginal('status');
                $newStatus = $bangkom->status;

                \App\Models\StatusHistory::create([
                    'bangkom_id' => $bangkom->getKey(),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'changed_by' => auth()->id(),
                    'changed_at' => now(),
                ]);
            }
        });
    }
}
