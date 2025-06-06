<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Standup extends Model
{
    /** @use HasFactory<\Database\Factories\StandupFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'accomplishment',
        'doing',
        'reflection',
        'date'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
