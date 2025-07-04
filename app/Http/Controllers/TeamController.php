<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Http\Requests\TeamCreationRequest;
use App\Http\Requests\TeamUpdateRequest;
use Spatie\Permission\Traits\HasRoles;

class TeamController extends Controller
{
    public function store(TeamCreationRequest $request)
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

    public function update(TeamUpdateRequest $request, Team $team)
    {

        $validated = $request->validated();

        $user = auth('api')->user();

        $userRoles = $user->roles->pluck('name')->toArray();
        $isCoach = in_array('coach', $userRoles);
        $isAdmin = in_array('admin', $userRoles);
        if ($isAdmin || ($isCoach && $team->coach_id == $user->id)) {
            $team->update([
                'gold_remaining' => $validated['gold_remaining'],
                'team_value' => $validated['team_value'],
            ]);
            return response()->json($team, 200);
        } else {
            return response()->json($team, 403);
        }

    }
}
