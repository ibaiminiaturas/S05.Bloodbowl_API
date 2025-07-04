<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Requests\UserLoginRequest;

class TeamController extends Controller
{
    public function store(UserLoginRequest $request)
    {
        $validated = $request->validated();

        $team = Team::create([
            'name' => $validated['name'],
            'coach_id' => $validated['coach_id'],
            'roster_id' => $validated['roster_id'],
            'gold_remaining' => $validated['gold_remaining'],
            'team_value' => $validated['team_value'],
        ]);

        return response()->json($team, 201);

    }
}
