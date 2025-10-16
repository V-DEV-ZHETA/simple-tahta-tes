<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model StatusHistory
 *
 * Model ini merepresentasikan riwayat perubahan status pengajuan permohonan
 * yang mencatat setiap perubahan status beserta informasi pelaku dan waktu.
 *
 * @property int $id
 * @property int $bangkom_id ID pengajuan yang berubah statusnya
 * @property string|null $status_sebelum Status sebelum perubahan
 * @property string|null $status_menjadi Status setelah perubahan
 * @property string|null $new_status Status baru (untuk kompatibilitas)
 * @property int|null $users_id ID pengguna yang melakukan perubahan
 * @property string|null $oleh Nama pengguna yang melakukan perubahan
 * @property string|null $catatan Catatan tambahan untuk perubahan status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read Bangkom $bangkom Relasi ke pengajuan yang berubah status
 * @property-read User|null $user Relasi ke pengguna yang melakukan perubahan
 */
class StatusHistory extends Model
{
    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bangkom_id',
        'status_sebelum',
        'status_menjadi',
        'new_status',
        'users_id',
        'oleh',
        'catatan',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Mendapatkan pengajuan yang mengalami perubahan status
     *
     * @return BelongsTo<Bangkom, StatusHistory>
     */
    public function bangkom(): BelongsTo
    {
        return $this->belongsTo(Bangkom::class);
    }

    /**
     * Mendapatkan pengguna yang melakukan perubahan status
     *
     * @return BelongsTo<User, StatusHistory>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
