<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleChangeRequest extends Model
{
    protected $fillable = [
        'schedule_id',
        'teacher_id',
        'new_day_of_week',
        'new_start_time',
        'new_end_time',
        'new_aula',
        'reason',
        'status',
        'reviewed_by',
        'admin_comments',
        'reviewed_at',
    ];

    protected $casts = [
        'new_start_time' => 'datetime:H:i',
        'new_end_time' => 'datetime:H:i',
        'reviewed_at' => 'datetime',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'aprobado');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rechazado');
    }
}
