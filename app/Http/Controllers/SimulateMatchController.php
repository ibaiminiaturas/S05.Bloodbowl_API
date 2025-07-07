<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SimulateMatchRequest;
use App\Models\Team;

class SimulateMatchController extends Controller
{
    public function post(SimulateMatchRequest $request)
    {
        $validated = $request->validated();

        $team1 = Team::with('teamPlayers.playerType')->findOrFail($validated['team_1_id']);
        $team2 = Team::with('teamPlayers.playerType')->findOrFail($validated['team_2_id']);
        $team1Result = $this->simulateTeamTouchdowns($team1);
        $team2Result = $this->simulateTeamTouchdowns($team2);

        $winner = null;
        if ($team1Result['touchdowns'] > $team2Result['touchdowns']) {
            $winner = $team1->name;
        } elseif ($team2Result['touchdowns'] > $team1Result['touchdowns']) {
            $winner = $team2->name;
        } else {
            $winner = 'Draw';
        }

        return response()->json([
            'team_1' => [
                'name' => $team1->name,
                'touchdowns' => $team1Result['touchdowns'],
                'scorers' => $team1Result['scorers'], // array con nombres y TDs por jugador
            ],
            'team_2' => [
                'name' => $team2->name,
                'touchdowns' => $team2Result['touchdowns'],
                'scorers' => $team2Result['scorers'],
            ],
            'winner' => $winner,
        ]);

    }

    protected function simulateTeamTouchdowns(Team $team)
    {
        $players = $team->teamPlayers->take(10); // 10 jugadores máximo

        $scorers = [];
        $totalTouchdowns = 0;

        foreach ($players as $player) {
            // Extraemos fuerza y skills para la fórmula
            $strength = $player->playerType->strength ?? 5;
            $skillCount = count($player->skills ?? []);

            // Probabilidad base de touchdown: fuerza + (skills * 1.5), con un máximo para evitar overflow
            $baseChance = min(10, $strength + ($skillCount * 1.5));

            // Random touchdowns 0 a baseChance (redondeado)
            $touchdowns = rand(0, (int)$baseChance);

            if ($touchdowns > 0) {
                $scorers[] = [
                    'player_name' => $player->name,
                    'touchdowns' => $touchdowns,
                ];
                $totalTouchdowns += $touchdowns;
            }
        }

        return [
            'touchdowns' => $totalTouchdowns,
            'scorers' => $scorers,
        ];
    }
}
