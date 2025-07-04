<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Http\Requests\TeamCreationRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Http\Requests\Request;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;

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

        $user = User::with('roles')->find(Auth::id());

        if ($user->hasRole('admin') || ($user->hasRole('coach') && $team->coach_id == $user->id)) {
            $team->update([
                'name' => $validated['name'],
                'team_value' => $validated['team_value'],
            ]);
            return response()->json($team, 200);
        } else {
            return response()->json($team, 403);
        }

    }

    public function index()
    {
        $teams = Team::with(['coach', 'roster'])->get();
        return response()->json(['data' => $teams]);
    }

    public function show(Team $team)
    {
        $user = User::with('roles')->find(Auth::id());

        if ($user->hasRole('admin') || ($user->hasRole('coach') && $team->coach_id == $user->id)) {
            return response()->json(['data' => $team], 200);
        } else {
            return response()->json(['data' => $team], 403);
        }
    }
}
