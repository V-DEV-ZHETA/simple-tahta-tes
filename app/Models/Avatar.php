<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Avatar
 *
 * Model ini merepresentasikan data avatar pengguna yang menyimpan
 * informasi profil lengkap termasuk gambar avatar.
 *
 * @property int $id
 * @property string $name Nama lengkap pengguna
 * @property string $email Alamat email pengguna (unik)
 * @property string|null $username Username untuk login (unik)
 * @property string|null $telepon Nomor telepon pengguna
 * @property string|null $avatar Path gambar avatar pengguna
 * @property string $password Password terenkripsi
 * @property \Carbon\Carbon|null $verified_at Waktu verifikasi akun
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Avatar extends Model
{
    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'telepon',
        'avatar',
        'password',
        'verified_at',
    ];

    /**
     * Casting atribut untuk tipe data yang tepat
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
