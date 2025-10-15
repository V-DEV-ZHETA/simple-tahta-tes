<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    protected $fillable = [
        'bangkom_id',
        'status_sebelum',
        'status_menjadi',
        'new_status',
        'users_id',
        'oleh',
        'catatan',
    ];

    /**
     * Relasi dengan Bangkom
     */
    public function bangkom()
    {
        return $this->belongsTo(Bangkom::class);
    }

    /**
     * Relasi dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}