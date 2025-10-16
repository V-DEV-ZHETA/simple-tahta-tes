<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Widyaiswara
 *
 * Model ini merepresentasikan data widyaiswara (instruktur/pelatih)
 * yang terlibat dalam kegiatan pelatihan dan pengembangan kompetensi.
 *
 * @property int $id
 * @property string|null $nip Nomor Induk Pegawai widyaiswara
 * @property string $nama Nama lengkap widyaiswara
 * @property string|null $satker Satuan kerja atau unit tempat widyaiswara bertugas
 * @property string|null $telpon Nomor telepon widyaiswara
 * @property string|null $email Alamat email widyaiswara
 * @property string|null $alamat Alamat lengkap widyaiswara
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Widyaiswara extends Model
{
    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip',
        'nama',
        'satker',
        'telpon',
        'email',
        'alamat',
    ];
}
