<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model JenisPelatihan
 *
 * Model ini merepresentasikan jenis atau kategori pelatihan
 * yang tersedia dalam sistem pengajuan permohonan.
 *
 * @property int $id
 * @property string $name Nama jenis pelatihan
 * @property string|null $deskripsi Deskripsi detail jenis pelatihan
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Bangkom> $bangkoms Relasi ke pengajuan yang menggunakan jenis pelatihan ini
 */
class JenisPelatihan extends Model
{
    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'deskripsi',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Mendapatkan semua pengajuan pelatihan yang menggunakan jenis ini
     *
     * @return HasMany<Bangkom>
     */
    public function bangkoms(): HasMany
    {
        return $this->hasMany(Bangkom::class);
    }
}
