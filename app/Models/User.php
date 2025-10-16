<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Model User
 *
 * Model ini merepresentasikan pengguna sistem yang dapat melakukan autentikasi
 * dan memiliki peran serta izin melalui Spatie Permission package.
 *
 * @property int $id
 * @property string $name Nama lengkap pengguna
 * @property string $email Alamat email pengguna (unik)
 * @property string|null $username Username untuk login (unik)
 * @property string|null $telepon Nomor telepon pengguna
 * @property int|null $instansi_id ID instansi tempat bekerja
 * @property string|null $satuan_kerja Satuan kerja dalam instansi
 * @property string $password Password terenkripsi
 * @property \Carbon\Carbon|null $email_verified_at Waktu verifikasi email
 * @property \Carbon\Carbon|null $verified_at Waktu verifikasi akun oleh admin
 * @property string|null $remember_token Token untuk remember me
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read Instansi|null $instansi Relasi ke model Instansi
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles Relasi ke roles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions Relasi ke permissions
 *
 * @method bool isVerified() Mengecek apakah user sudah diverifikasi
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'telepon',
        'instansi_id',
        'satuan_kerja',
        'password',
        'verified_at',
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut untuk tipe data yang tepat
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Mendapatkan instansi tempat user bekerja
     *
     * @return BelongsTo<Instansi, User>
     */
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    // ==================== MODEL EVENTS ====================

    /**
     * Boot method untuk menangani event model
     * Menggunakan observer untuk memantau perubahan pada model User
     */
    protected static function booted(): void
    {
        // Mendaftarkan observer untuk memantau aktivitas user
        static::observe(\App\Observers\UserObserver::class);
    }

    // ==================== CUSTOM METHODS ====================

    /**
     * Mengecek apakah user sudah diverifikasi oleh admin
     * User yang sudah diverifikasi memiliki akses penuh ke sistem
     *
     * @return bool True jika sudah diverifikasi, false jika belum
     */
    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }
}
