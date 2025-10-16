<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Tahun
 *
 * Model ini merepresentasikan tahun akademik atau periode pelatihan
 * yang digunakan untuk mengorganisir kegiatan berdasarkan tahun.
 *
 * @property int $id
 * @property int $year Tahun periode (contoh: 2024)
 * @property bool $status Status aktif/nonaktif tahun ini
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Bangkom> $bangkoms Relasi ke pengajuan dalam tahun ini
 */
class Tahun extends Model
{
    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'year',
        'status',
    ];

    /**
     * Casting atribut untuk tipe data yang tepat
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year' => 'integer',
        'status' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Mendapatkan semua pengajuan pelatihan dalam tahun ini
     *
     * @return HasMany<Bangkom>
     */
    public function bangkoms(): HasMany
    {
        return $this->hasMany(Bangkom::class);
    }
}
