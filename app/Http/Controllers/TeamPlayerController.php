<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Http\Requests\TeamPlayerCreationRequest;
use App\Models\TeamPlayer;
use App\Models\PlayerType;
use App\Models\Team;
use App\Models\Roster;

class TeamPlayerController extends Controller
{
    public function store(TeamPlayerCreationRequest $request, Team $team)
    {
        $validated = $request->validated();

        $teamPlayer = TeamPlayer::create([
            'name' => $validated['name'],
            'team_id' => $team->id,
            'player_type_id' => $validated['player_type_id'],
            'player_number' => $validated['player_number'],
            'injuries' => $validated['injuries'],
            'spp' => $validated['spp'],
        ]);


        $gold = PlayerType::findOrFail($validated['player_type_id'])->cost;

        $team->gold_remaining = $team->gold_remaining - $gold;

        $team->save();

        return response()->json($teamPlayer, 201);

    }
}
