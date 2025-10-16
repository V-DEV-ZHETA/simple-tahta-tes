<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Instansi
 *
 * Model ini merepresentasikan data instansi atau organisasi
 * yang mengajukan permohonan pelatihan atau memiliki pengguna terdaftar.
 *
 * @property int $id
 * @property string $name Nama instansi atau organisasi
 * @property string|null $description Deskripsi singkat tentang instansi
 * @property string|null $address Alamat lengkap instansi
 * @property string|null $phone Nomor telepon instansi
 * @property string|null $email Alamat email instansi
 * @property string|null $website Situs web instansi
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users Relasi ke pengguna yang berasal dari instansi ini
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Bangkom> $bangkoms Relasi ke pengajuan yang dibuat oleh instansi ini
 */
class Instansi extends Model
{
    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'email',
        'website',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Mendapatkan semua pengguna yang berasal dari instansi ini
     *
     * @return HasMany<User>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Mendapatkan semua pengajuan pelatihan yang dibuat oleh instansi ini
     *
     * @return HasMany<Bangkom>
     */
    public function bangkoms(): HasMany
    {
        return $this->hasMany(Bangkom::class);
    }
}
