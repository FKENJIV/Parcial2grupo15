<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Group;
use App\Models\User;

class Subject extends Model
{
    protected $fillable = ['name', 'code', 'description', 'credits', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Scope to filter active subjects (PostgreSQL compatible)
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereRaw('active = true');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'subject_id');
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subject_user')
            ->withTimestamps();
    }
}
