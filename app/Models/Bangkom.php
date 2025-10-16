<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use App\Models\Instansi;
use App\Models\JenisPelatihan;
use App\Models\Sasaran;
use App\Models\BentukPelatihan;
use App\Models\PermohonanFile;
use App\Models\StatusHistory;
use App\Enums\BangkomStatus;

/**
 * Model Bangkom (Pengajuan Permohonan)
 *
 * Model ini merepresentasikan data pengajuan permohonan pelatihan (Bangkom)
 * yang mencakup informasi kegiatan, instansi, jenis pelatihan, dan status.
 *
 * @property int $id
 * @property int $users_id ID pengguna yang membuat pengajuan
 * @property int|null $instansi_id ID instansi yang mengajukan
 * @property string|null $unit_kerja Unit kerja dalam instansi
 * @property string $nama_kegiatan Nama kegiatan pelatihan
 * @property string|null $kode_kegiatan Kode unik kegiatan
 * @property int|null $jenis_pelatihan_id ID jenis pelatihan
 * @property int|null $bentuk_pelatihan_id ID bentuk pelatihan
 * @property int|null $sasaran_id ID sasaran peserta
 * @property \Carbon\Carbon|null $tanggal_mulai Tanggal mulai kegiatan
 * @property \Carbon\Carbon|null $tanggal_selesai Tanggal selesai kegiatan
 * @property string|null $tempat Tempat pelaksanaan kegiatan
 * @property string|null $alamat Alamat lengkap lokasi kegiatan
 * @property int|null $kuota Jumlah kuota peserta
 * @property string|null $nama_panitia Nama panitia penyelenggara
 * @property string|null $no_telp Nomor telepon panitia
 * @property string|null $narasumber Nama narasumber atau instruktur
 * @property BangkomStatus $status Status pengajuan (enum)
 * @property array|null $kurikulum Daftar kurikulum kegiatan
 * @property string|null $deskripsi Deskripsi detail kegiatan
 * @property string|null $persyaratan Persyaratan khusus untuk peserta
 * @property string|null $file_permohonan Path file permohonan
 * @property string|null $bahan_tayang Materi atau bahan tayang
 * @property string|null $pelaporan Laporan kegiatan
 * @property string|null $absensi Daftar absensi peserta
 * @property string|null $surat_ttd Surat tanda tangan
 * @property string|null $contoh_sertifikat Contoh sertifikat
 * @property array|null $dokumentasi File dokumentasi kegiatan
 * @property string|null $catatan Catatan tambahan
 * @property array|null $file_sttp File STTP (Surat Tanda Tamat Pendidikan)
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @property-read User $user Relasi ke model User
 * @property-read Instansi|null $instansi Relasi ke model Instansi
 * @property-read JenisPelatihan|null $jenisPelatihan Relasi ke model JenisPelatihan
 * @property-read BentukPelatihan|null $bentukPelatihan Relasi ke model BentukPelatihan
 * @property-read Sasaran|null $sasaran Relasi ke model Sasaran
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PermohonanFile> $permohonanFiles Relasi ke file permohonan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, StatusHistory> $historiStatuses Riwayat perubahan status
 */
class Bangkom extends Model
{
    use SoftDeletes;

    /**
     * Relasi yang selalu di-load secara otomatis untuk mengoptimalkan query
     */
    protected $with = [
        'jenisPelatihan',
        'bentukPelatihan',
        'sasaran',
    ];

    /**
     * Atribut yang dapat diisi secara massal
     */
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
        'narasumber',
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
        'file_sttp',
    ];

    /**
     * Casting atribut untuk tipe data yang tepat
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'kuota' => 'integer',
        'kurikulum' => 'array',
        'dokumentasi' => 'array',
        'file_sttp' => 'array',
        'status' => BangkomStatus::class,
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Mendapatkan user yang membuat pengajuan ini
     *
     * @return BelongsTo<User, Bangkom>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * Mendapatkan instansi yang mengajukan pelatihan
     *
     * @return BelongsTo<Instansi, Bangkom>
     */
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    /**
     * Mendapatkan jenis pelatihan yang dipilih
     *
     * @return BelongsTo<JenisPelatihan, Bangkom>
     */
    public function jenisPelatihan(): BelongsTo
    {
        return $this->belongsTo(JenisPelatihan::class);
    }

    /**
     * Mendapatkan bentuk pelatihan yang dipilih
     *
     * @return BelongsTo<BentukPelatihan, Bangkom>
     */
    public function bentukPelatihan(): BelongsTo
    {
        return $this->belongsTo(BentukPelatihan::class);
    }

    /**
     * Mendapatkan sasaran peserta pelatihan
     *
     * @return BelongsTo<Sasaran, Bangkom>
     */
    public function sasaran(): BelongsTo
    {
        return $this->belongsTo(Sasaran::class);
    }

    /**
     * Mendapatkan file-file permohonan yang terkait
     *
     * @return HasMany<PermohonanFile>
     */
    public function permohonanFiles(): HasMany
    {
        return $this->hasMany(PermohonanFile::class);
    }

    /**
     * Mendapatkan riwayat perubahan status pengajuan
     * Diurutkan berdasarkan waktu pembuatan terbaru
     *
     * @return HasMany<StatusHistory>
     */
    public function historiStatuses(): HasMany
    {
        return $this->hasMany(StatusHistory::class)->orderBy('created_at', 'desc');
    }

    // ==================== MODEL EVENTS ====================

    /**
     * Boot method untuk menangani event model
     * Digunakan untuk mencatat perubahan status secara otomatis
     */
    protected static function booted(): void
    {
        // Event ketika model sedang diupdate
        static::updating(function (Bangkom $bangkom): void {
            // Cek apakah status berubah
            if ($bangkom->isDirty('status')) {
                // Ambil status lama dan baru
                $oldStatus = $bangkom->getOriginal('status');
                $newStatus = $bangkom->status;

                // Buat record riwayat status baru
                StatusHistory::create([
                    'bangkom_id' => $bangkom->getKey(),
                    'status_sebelum' => $oldStatus instanceof BangkomStatus ? $oldStatus->value : $oldStatus,
                    'status_menjadi' => $newStatus instanceof BangkomStatus ? $newStatus->value : $newStatus,
                    'new_status' => $newStatus instanceof BangkomStatus ? $newStatus->value : $newStatus, // Untuk kompatibilitas
                    'users_id' => Auth::id(),
                    'oleh' => Auth::user()?->name ?? 'System',
                    'catatan' => 'Perubahan status pengajuan',
                ]);
            }
        });
    }
}
