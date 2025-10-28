<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Group;

class Schedule extends Model
{
    protected $fillable = ['group_id', 'day_of_week', 'start_time', 'end_time', 'aula'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
