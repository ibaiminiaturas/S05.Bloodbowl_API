<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TeamPlayerCreationRequest;
use App\Models\TeamPlayer;

class TeamPlayerController extends Controller
{
 public function store(TeamPlayerCreationRequest $request)
    {
        $validated = $request->validated();

        $teamPlayer = TeamPlayer::create([
            'name' => $validated['name'],
            'player_type_id' => $validated['player_type_id'],
            'player_number' => $validated['player_number'],
            'injuries' => $validated['injuries'],
            'spp' => $validated['spp'],
        ]);

        return response()->json($team, 201);

    }
}
