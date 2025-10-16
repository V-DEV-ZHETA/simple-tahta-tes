<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Category
 *
 * Model ini merepresentasikan kategori umum yang dapat digunakan
 * untuk mengelompokkan berbagai entitas dalam sistem.
 *
 * @property int $id
 * @property string $name Nama kategori
 * @property string|null $description Deskripsi kategori
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Category extends Model
{
    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];
}
