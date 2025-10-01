<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Instansi;
use App\Models\JenisPelatihan;
use App\Models\Sasaran;

class Bangkom extends Model
{
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
}