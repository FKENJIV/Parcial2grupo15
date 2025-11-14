<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleHistory extends Model
{
    protected $fillable = [
        'schedule_id',
        'changed_by',
        'change_type',
        'old_values',
        'new_values',
        'reason',
        'change_request_id',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function changeRequest(): BelongsTo
    {
        return $this->belongsTo(ScheduleChangeRequest::class, 'change_request_id');
    }
}
