<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\User;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Attendance;

class Group extends Model
{
    // 'group_name' is a virtual/backward-compatible attribute (accessor)
    // The DB table has `name`, `subject`, `capacity`, `teacher_id`.
    // Keep fillable limited to actual DB columns to avoid accidental writes
    // of non-existent columns (which cause SQL errors on Postgres).
    protected $fillable = ['name', 'subject', 'subject_id', 'capacity', 'teacher_id'];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subjectModel(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function attendances(): HasManyThrough
    {
        return $this->hasManyThrough(Attendance::class, Schedule::class);
    }

    /**
     * Backward-compatible accessor for group_name.
     * If `group_name` is empty, fall back to `name` or `code`.
     */
    public function getGroupNameAttribute(): ?string
    {
        if (!empty($this->attributes['group_name'])) {
            return $this->attributes['group_name'];
        }

        if (!empty($this->attributes['name'])) {
            return $this->attributes['name'];
        }

        return $this->attributes['code'] ?? null;
    }

    /**
     * When setting group_name, keep the DB `name` column in sync for migrations
     * or other code that reads `name` directly.
     */
    public function setGroupNameAttribute(?string $value): void
    {
        $this->attributes['group_name'] = $value;
        // also set the canonical name column to avoid null 'name' DB errors
        if (!empty($value) && empty($this->attributes['name'])) {
            $this->attributes['name'] = $value;
        }
    }

    /**
     * Backward-compatible accessor for `code` used throughout views.
     * Prefer explicit `code` attribute if present; otherwise fall back to `name`.
     */
    public function getCodeAttribute(): ?string
    {
        return $this->attributes['code'] ?? $this->attributes['name'] ?? null;
    }

    /**
     * Provide `max_students` accessor mapped to the DB `capacity` column.
     */
    public function getMaxStudentsAttribute(): ?int
    {
        return isset($this->attributes['max_students']) ? (int) $this->attributes['max_students'] : (isset($this->attributes['capacity']) ? (int) $this->attributes['capacity'] : null);
    }

    /**
     * When code sets `max_students`, persist it into the `capacity` DB column.
     */
    public function setMaxStudentsAttribute($value): void
    {
        $this->attributes['capacity'] = $value;
        $this->attributes['max_students'] = $value;
    }

    /**
     * Classroom may not exist in the DB schema; expose it if present.
     */
    public function getClassroomAttribute(): ?string
    {
        return $this->attributes['classroom'] ?? null;
    }
}
