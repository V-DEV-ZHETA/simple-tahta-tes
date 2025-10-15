<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\Instansi;
use App\Models\JenisPelatihan;
use App\Models\Sasaran;
use App\Models\BentukPelatihan;
use App\Models\PermohonanFile;
use App\Models\StatusHistory;
use App\Enums\BangkomStatus;

class Bangkom extends Model
{
    use SoftDeletes;

    /**
     * Relasi yang selalu di-load (eager loading)
     */
    protected $with = [
        'jenisPelatihan',
        'bentukPelatihan',
        'sasaran',
    ];

    protected $fillable = [
        'users_id',
        'instansi_id',
        'unit_kerja',
        'nama_kegiatan',
        'kode_kegiatan',
        'jenis_pelatihan_id',
        'bentuk_pelatihan_id',
        'sasaran_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'tempat',
        'alamat',
        'kuota',
        'nama_panitia',
        'no_telp',
        'status',
        'kurikulum',
        'deskripsi',
        'persyaratan',
        'file_permohonan',
        'bahan_tayang',
        'pelaporan',
        'absensi',
        'surat_ttd',
        'contoh_sertifikat',
        'dokumentasi',
        'catatan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'kuota' => 'integer',
        'kurikulum' => 'array',
        'dokumentasi' => 'array',
        'status' => BangkomStatus::class,
    ];

    /**
     * Relasi dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * Relasi dengan Instansi
     */
    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    /**
     * Relasi dengan Jenis Pelatihan
     */
    public function jenisPelatihan()
    {
        return $this->belongsTo(JenisPelatihan::class);
    }

    /**
     * Relasi dengan Bentuk Pelatihan
     */
    public function bentukPelatihan()
    {
        return $this->belongsTo(BentukPelatihan::class);
    }

    /**
     * Relasi dengan Sasaran
     */
    public function sasaran()
    {
        return $this->belongsTo(Sasaran::class);
    }

    /**
     * Relasi dengan Permohonan Files
     */
    public function permohonanFiles()
    {
        return $this->hasMany(PermohonanFile::class);
    }

    /**
     * Relasi dengan Status History
     */
    public function historiStatuses()
    {
        return $this->hasMany(StatusHistory::class);
    }

    /**
     * Boot method untuk event model
     */
    protected static function booted()
    {
        static::updating(function ($bangkom) {
            if ($bangkom->isDirty('status')) {
                $oldStatus = $bangkom->getOriginal('status');
                $newStatus = $bangkom->status;

                StatusHistory::create([
                    'bangkom_id' => $bangkom->getKey(),
                    'status_sebelum' => $oldStatus instanceof BangkomStatus ? $oldStatus->value : $oldStatus,
                    'status_menjadi' => $newStatus instanceof BangkomStatus ? $newStatus->value : $newStatus,
                    'new_status' => $newStatus instanceof BangkomStatus ? $newStatus->value : $newStatus, // Tambahkan ini
                    'users_id' => Auth::id(),
                    'oleh' => Auth::user()?->name ?? 'System',
                    'catatan' => 'Pengajuan Permohonan',
                ]);
            }
        });
    }
}