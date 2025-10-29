<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Group;

class Attendance extends Model
{
    protected $fillable = ['teacher_id', 'schedule_id', 'group_id', 'status', 'notes', 'aula', 'registered_at'];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
