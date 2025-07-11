<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Org extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'timezone',
    ];

    /**
     * Get the users for the organization
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get events for this organization through users
     */
    public function events(): HasMany
    {
        return $this->hasManyThrough(Event::class, User::class);
    }
}
