<?php

namespace App\Models;

use App\Enums\JalurPelatihan;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model BentukPelatihan
 *
 * Model ini merepresentasikan bentuk atau metode pelaksanaan pelatihan
 * yang tersedia dalam sistem, seperti tatap muka, daring, dll.
 *
 * @property int $id
 * @property \App\Enums\JalurPelatihan $jalur Jalur pelatihan (enum: internal/eksternal)
 * @property string $bentuk Nama bentuk pelatihan (tatap muka, daring, dll)
 * @property string|null $deskripsi Deskripsi detail bentuk pelatihan
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Bangkom> $bangkoms Relasi ke pengajuan yang menggunakan bentuk pelatihan ini
 */
class BentukPelatihan extends Model
{
    use HasUlids;

    /**
     * Nama tabel yang digunakan model ini
     *
     * @var string
     */
    protected $table = 'bentuk_pelatihans';

    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jalur',
        'bentuk',
        'deskripsi',
    ];

    /**
     * Casting atribut untuk tipe data yang tepat
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jalur' => JalurPelatihan::class,
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Mendapatkan semua pengajuan pelatihan yang menggunakan bentuk ini
     *
     * @return HasMany<Bangkom>
     */
    public function bangkoms(): HasMany
    {
        return $this->hasMany(Bangkom::class);
    }
}
