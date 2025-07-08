<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SimulateMatchRequest;
use App\Models\Team;
/**
 * @OA\Post(
 *     path="/api/simulate-match",
 *     summary="Simulate a match between two teams",
 *     tags={"Match Simulation"},
 *     description="Simulates touchdowns scored by players of two teams and returns results with the winner.",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"team_1_id", "team_2_id"},
 *             @OA\Property(property="team_1_id", type="integer", example=1, description="ID of the first team"),
 *             @OA\Property(property="team_2_id", type="integer", example=2, description="ID of the second team")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Match simulated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "team_1": {
 *                     "name": "Team A",
 *                     "touchdowns": 3,
 *                     "scorers": {
 *                         {"player_name": "John Doe", "touchdowns": 2},
 *                         {"player_name": "Mike Brown", "touchdowns": 1}
 *                     }
 *                 },
 *                 "team_2": {
 *                     "name": "Team B",
 *                     "touchdowns": 1,
 *                     "scorers": {
 *                         {"player_name": "Jane Smith", "touchdowns": 1}
 *                     }
 *                 },
 *                 "winner": "Team A"
 *             },
 *             @OA\Property(
 *                 property="team_1",
 *                 type="object",
 *                 @OA\Property(property="name", type="string", example="Team A"),
 *                 @OA\Property(property="touchdowns", type="integer", example=3),
 *                 @OA\Property(
 *                     property="scorers",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="player_name", type="string", example="John Doe"),
 *                         @OA\Property(property="touchdowns", type="integer", example=2)
 *                     )
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="team_2",
 *                 type="object",
 *                 @OA\Property(property="name", type="string", example="Team B"),
 *                 @OA\Property(property="touchdowns", type="integer", example=1),
 *                 @OA\Property(
 *                     property="scorers",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="player_name", type="string", example="Jane Smith"),
 *                         @OA\Property(property="touchdowns", type="integer", example=1)
 *                     )
 *                 )
 *             ),
 *             @OA\Property(property="winner", type="string", example="Team A")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error (e.g., missing or invalid team IDs)"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized. Invalid or missing token."
 *     )
 * )
 */

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
