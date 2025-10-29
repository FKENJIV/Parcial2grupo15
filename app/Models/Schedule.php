<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Group;
use App\Models\Attendance;

class Schedule extends Model
{
    protected $fillable = ['group_id', 'day_of_week', 'start_time', 'end_time', 'aula'];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // Accessor for backward compatibility with 'day' attribute
    protected function day(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->day_of_week,
            set: fn ($value) => ['day_of_week' => $value],
        );
    }

    // Accessor for backward compatibility with 'time_block' attribute
    // Extracts the hour from start_time
    protected function timeBlock(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->start_time) {
                    // If start_time is a string like "08:00:00", extract hour
                    if (is_string($this->start_time)) {
                        return (int) substr($this->start_time, 0, 2);
                    }
                    // If it's a Carbon instance
                    return $this->start_time->hour;
                }
                return null;
            }
        );
    }
}
