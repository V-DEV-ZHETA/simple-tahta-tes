<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusHistory extends Model
{
    protected $fillable = [
        'bangkom_id',
        'old_status',
        'new_status',
        'changed_by',
        'changed_at',
    ];

    protected $dates = [
        'changed_at',
    ];

    public function bangkom(): BelongsTo
    {
        return $this->belongsTo(Bangkom::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
