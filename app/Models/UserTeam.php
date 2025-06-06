<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserTeam extends Pivot
{
    //
    protected $table = 'user_team';
}
