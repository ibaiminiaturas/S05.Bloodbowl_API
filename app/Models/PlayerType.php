<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerType extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerTypeFactory> */

    public function roster()
    {
        return $this->belongsTo(Roster::class);
    }
}
