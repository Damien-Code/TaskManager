<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function standups(): HasMany
    {
        return $this->hasMany(Standup::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_team');
    }
}
