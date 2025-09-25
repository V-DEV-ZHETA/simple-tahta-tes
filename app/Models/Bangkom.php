<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Instansi;
use App\Models\JenisPelatihan;
use App\Models\BentukPelatihan;
use App\Models\Sasaran;

class Bangkom extends Model
{
    protected $fillable = [
        'instansi_id',
        'unit_kerja',
        'nama_kegiatan',
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
    ];

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
        return $this->belongsTo(BentukPelatihan::class);
    }

    public function sasaran()
    {
        return $this->belongsTo(Sasaran::class);
    }
}
