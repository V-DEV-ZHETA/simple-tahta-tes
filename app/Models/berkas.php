<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Berkas
 *
 * Model ini merepresentasikan berkas atau dokumen yang terkait
 * dengan berbagai entitas dalam sistem pelatihan.
 *
 * @property int $id
 * @property string|null $nama_berkas Nama berkas atau dokumen
 * @property string|null $path Path lokasi penyimpanan berkas
 * @property string|null $tipe Tipe berkas (pdf, doc, jpg, dll)
 * @property int|null $ukuran Ukuran berkas dalam bytes
 * @property string|null $deskripsi Deskripsi berkas
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class berkas extends Model
{
    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_berkas',
        'path',
        'tipe',
        'ukuran',
        'deskripsi',
    ];

    /**
     * Casting atribut untuk tipe data yang tepat
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ukuran' => 'integer',
    ];
}
