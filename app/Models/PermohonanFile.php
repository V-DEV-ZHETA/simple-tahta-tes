<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model PermohonanFile
 *
 * Model ini merepresentasikan file-file yang terkait dengan pengajuan permohonan
 * seperti dokumen permohonan, surat dukungan, dll.
 *
 * @property int $id
 * @property int $bangkom_id ID pengajuan yang terkait
 * @property string $file_path Path file yang diupload
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read Bangkom $bangkom Relasi ke model Bangkom
 */
class PermohonanFile extends Model
{
    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bangkom_id',
        'file_path',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Mendapatkan pengajuan yang terkait dengan file ini
     *
     * @return BelongsTo<Bangkom, PermohonanFile>
     */
    public function bangkom(): BelongsTo
    {
        return $this->belongsTo(Bangkom::class);
    }
}
