<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Mentoring
 *
 * Model ini merepresentasikan kegiatan mentoring atau pembimbingan
 * yang dilakukan oleh widyaiswara kepada instansi tertentu.
 *
 * @property int $id
 * @property string $nama_mentoring Nama kegiatan mentoring
 * @property int|null $instansi_id ID instansi yang menerima mentoring
 * @property int|null $widyaiswara_id ID widyaiswara yang memberikan mentoring
 * @property \Carbon\Carbon|null $tanggal_mulai Tanggal mulai mentoring
 * @property \Carbon\Carbon|null $tanggal_berakhir Tanggal berakhir mentoring
 * @property int|null $kuota Jumlah kuota peserta mentoring
 * @property string|null $status Status mentoring (aktif/nonaktif)
 * @property string|null $deskripsi Deskripsi detail mentoring
 * @property string|null $persyaratan Persyaratan untuk mengikuti mentoring
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read Instansi|null $instansi Relasi ke model Instansi
 * @property-read Widyaiswara|null $widyaiswara Relasi ke model Widyaiswara
 */
class Mentoring extends Model
{
    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_mentoring',
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
     * Mendapatkan instansi yang menerima mentoring
     *
     * @return BelongsTo<Instansi, Mentoring>
     */
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    /**
     * Mendapatkan widyaiswara yang memberikan mentoring
     *
     * @return BelongsTo<Widyaiswara, Mentoring>
     */
    public function widyaiswara(): BelongsTo
    {
        return $this->belongsTo(Widyaiswara::class);
    }
}
