<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Model Permission
 *
 * Model ini merepresentasikan izin (permission) dalam sistem yang menggunakan
 * Spatie Permission package untuk manajemen role dan permission.
 *
 * @property int $id
 * @property string $name Nama permission (unik)
 * @property string|null $guard_name Guard name untuk permission ini
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Role> $roles Relasi many-to-many dengan roles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users Relasi many-to-many dengan users
 */
class Permission extends SpatiePermission
{
    // Menggunakan semua fitur dari SpatiePermission tanpa modifikasi
    // Model ini mewarisi semua method dan property dari Spatie\Permission\Models\Permission
}
