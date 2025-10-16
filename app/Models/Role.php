<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Model Role
 *
 * Model ini merepresentasikan peran (role) dalam sistem yang menggunakan
 * Spatie Permission package untuk manajemen role dan permission.
 *
 * @property int $id
 * @property string $name Nama role (unik)
 * @property string|null $guard_name Guard name untuk role ini
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions Relasi many-to-many dengan permissions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users Relasi many-to-many dengan users
 */
class Role extends SpatieRole
{
    // Menggunakan semua fitur dari SpatieRole tanpa modifikasi
    // Model ini mewarisi semua method dan property dari Spatie\Permission\Models\Role
}
