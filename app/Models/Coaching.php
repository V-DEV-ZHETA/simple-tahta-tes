<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Coaching
 *
 * Model ini merepresentasikan kegiatan coaching atau pendampingan
 * yang dilakukan oleh widyaiswara kepada instansi tertentu.
 *
 * @property int $id
 * @property string $nama_coaching Nama kegiatan coaching
 * @property int|null $instansi_id ID instansi yang menerima coaching
 * @property int|null $widyaiswara_id ID widyaiswara yang memberikan coaching
 * @property \Carbon\Carbon|null $tanggal_mulai Tanggal mulai coaching
 * @property \Carbon\Carbon|null $tanggal_berakhir Tanggal berakhir coaching
 * @property int|null $kuota Jumlah kuota peserta coaching
 * @property string|null $status Status coaching (aktif/nonaktif)
 * @property string|null $deskripsi Deskripsi detail coaching
 * @property string|null $persyaratan Persyaratan untuk mengikuti coaching
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read Instansi|null $instansi Relasi ke model Instansi
 * @property-read Widyaiswara|null $widyaiswara Relasi ke model Widyaiswara
 */
class Coaching extends Model
{
    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_coaching',
        'instansi_id',
        'widyaiswara_id',
        'tanggal_mulai',
        'tanggal_berakhir',
        'kuota',
        'status',
        'deskripsi',
        'persyaratan',
    ];

    /**
     * Casting atribut untuk tipe data yang tepat
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'kuota' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Mendapatkan instansi yang menerima coaching
     *
     * @return BelongsTo<Instansi, Coaching>
     */
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    /**
     * Mendapatkan widyaiswara yang memberikan coaching
     *
     * @return BelongsTo<Widyaiswara, Coaching>
     */
    public function widyaiswara(): BelongsTo
    {
        return $this->belongsTo(Widyaiswara::class);
    }
}
