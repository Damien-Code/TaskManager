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
}
