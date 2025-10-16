<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Bidang
 *
 * Model ini merepresentasikan bidang atau kategori kegiatan pelatihan
 * yang digunakan untuk mengelompokkan jenis-jenis pelatihan.
 *
 * @property int $id
 * @property string $name Nama bidang pelatihan
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Bangkom> $bangkoms Relasi ke pengajuan yang termasuk dalam bidang ini
 */
class Bidang extends Model
{
    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Mendapatkan semua pengajuan pelatihan dalam bidang ini
     *
     * @return HasMany<Bangkom>
     */
    public function bangkoms(): HasMany
    {
        return $this->hasMany(Bangkom::class);
    }
}
