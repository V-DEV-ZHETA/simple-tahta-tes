<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Sasaran
 *
 * Model ini merepresentasikan sasaran atau target peserta pelatihan
 * seperti pegawai negeri, swasta, mahasiswa, dll.
 *
 * @property int $id
 * @property string $name Nama sasaran peserta
 * @property string|null $deskripsi Deskripsi detail sasaran peserta
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Bangkom> $bangkoms Relasi ke pengajuan yang menargetkan sasaran ini
 */
class Sasaran extends Model
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
     * Mendapatkan semua pengajuan pelatihan yang menargetkan sasaran ini
     *
     * @return HasMany<Bangkom>
     */
    public function bangkoms(): HasMany
    {
        return $this->hasMany(Bangkom::class);
    }
}
