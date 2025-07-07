<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamPlayer extends Model
{
    protected $fillable = [
        'name',
        'player_number',
        'player_type_id',
        'team_id',
        'injuries',
        'spp'
    ];

    public function playerType()
    {
        return $this->belongsTo(PlayerType::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
