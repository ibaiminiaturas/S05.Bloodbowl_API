<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'coach_id',
        'roster_id',
        'gold_remaining',
        'team_value',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function roster()
    {
        return $this->belongsTo(Roster::class);
    }
}
