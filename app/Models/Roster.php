<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function playerTypes()
    {
        return $this->hasMany(PlayerType::class);
    }
}
