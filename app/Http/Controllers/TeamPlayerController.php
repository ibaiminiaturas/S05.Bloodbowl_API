<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Http\Requests\TeamPlayerCreationRequest;
use App\Http\Requests\TeamPlayerUpdateDeleteRequest;
use App\Models\TeamPlayer;
use App\Models\PlayerType;
use App\Models\Team;
use App\Models\Roster;

class TeamPlayerController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/teams/{team}/players",
 *     summary="Add a player to a team",
 *     operationId="addPlayerToTeam",
 *     tags={"Team Players"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="team",
 *         in="path",
 *         required=true,
 *         description="ID of the team to which the player will be added",
 *         @OA\Schema(type="integer", example=194)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"player_type_id", "player_number", "spp"},
 *             @OA\Property(property="player_type_id", type="integer", example=13, description="ID of the player type"),
 *             @OA\Property(property="player_number", type="integer", example=4, description="Unique number of the player in the team"),
 *             @OA\Property(property="name", type="string", example="Morg 'n Thorg", description="Player's name (optional but must be unique per team)"),
 *             @OA\Property(property="injuries", type="string", nullable=true, example="Fractured Skull", description="Optional injury notes"),
 *             @OA\Property(property="spp", type="integer", minimum=0, maximum=20, example=5, description="Star Player Points")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Player successfully added to the team",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=141),
 *             @OA\Property(property="team_id", type="integer", example=194),
 *             @OA\Property(property="player_type_id", type="integer", example=13),
 *             @OA\Property(property="name", type="string", example="Morg 'n Thorg"),
 *             @OA\Property(property="player_number", type="integer", example=4),
 *             @OA\Property(property="injuries", type="string", example="Fractured Skull"),
 *             @OA\Property(property="spp", type="integer", example=5),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-08T12:00:00.000000Z"),
 *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-08T12:00:00.000000Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="player_number",
 *                     type="array",
 *                     @OA\Items(type="string", example="The player number has already been taken.")
 *                 ),
 *                 @OA\Property(
 *                     property="name",
 *                     type="array",
 *                     @OA\Items(type="string", example="The name has already been taken.")
 *                 ),
 *                 @OA\Property(
 *                     property="spp",
 *                     type="array",
 *                     @OA\Items(type="string", example="The spp must be at most 20.")
 *                 ),
 *                 @OA\Property(
 *                     property="player_type_id",
 *                     type="array",
 *                     @OA\Items(type="string", example="You already have the maximum of that kind of player.")
 *                 ),
 *                 @OA\Property(
 *                     property="general",
 *                     type="array",
 *                     @OA\Items(type="string", example="The Team has not enough gold. Only XXXXXX gold remaining.")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden - team does not belong to the user",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The team does not belong to the user.")
 *         )
 *     )
 * )
 */

    public function store(TeamPlayerCreationRequest $request, Team $team)
    {
        $validated = $request->validated();
        $validated['spp'] = $validated['spp'] ?? 0;
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

    /**
     * @OA\Put(
     *     path="/api/team-players/{teamPlayer}",
     *     summary="Update a team player's injuries or SPP",
     *     operationId="updateTeamPlayer",
     *     tags={"Team Players"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="teamPlayer",
     *         in="path",
     *         required=true,
     *         description="ID of the team player to update",
     *         @OA\Schema(type="integer", example=141)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"spp"},
     *             @OA\Property(property="injuries", type="string", maxLength=255, nullable=true, example="Broken Arm"),
     *             @OA\Property(property="spp", type="integer", minimum=0, maximum=20, example=12)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Player updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Player updated successfully"),
     *             @OA\Property(property="player", type="integer", example=141)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="spp",
     *                     type="array",
     *                     @OA\Items(type="string", example="The spp must be between 0 and 20.")
     *                 ),
     *                 @OA\Property(
     *                     property="injuries",
     *                     type="array",
     *                     @OA\Items(type="string", example="The injuries must not be greater than 255 characters.")
     *                 ),
     *                 @OA\Property(
     *                     property="general",
     *                     type="array",
     *                     @OA\Items(type="string", example="Could not verify the player.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - user does not own the team",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The team does not belong to the user.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Team player not found"
     *     )
     * )
     */

    public function update(TeamPlayerUpdateDeleteRequest $request, TeamPlayer $teamPlayer)
    {
        $validated = $request->validated();

        // Solo actualizar los campos que estén presentes en la request validada
        $dataToUpdate = [];

        if ($request->has('injuries')) {
            $dataToUpdate['injuries'] = $validated['injuries'];
        }

        if ($request->has('spp')) {
            $dataToUpdate['spp'] = $validated['spp'];
        }

        $teamPlayer->update($dataToUpdate);

        return response()->json([
            'message' => 'Player updated successfully',
            'player' => $teamPlayer,
        ], 200);


    }


    /**
 * @OA\Delete(
 *     path="/api/team-players/{teamPlayer}",
 *     summary="Delete a team player and refund their cost",
 *     operationId="deleteTeamPlayer",
 *     tags={"Team Players"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="teamPlayer",
 *         in="path",
 *         required=true,
 *         description="ID of the team player to delete",
 *         @OA\Schema(type="integer", example=145)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Player deleted successfully and gold refunded to the team",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Player deleted successfully"),
 *             @OA\Property(property="player", type="string", example="Dr. Summer Bins Jr.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="general",
 *                     type="array",
 *                     @OA\Items(type="string", example="Could not verify the player.")
 *                 ),
 *                 @OA\Property(
 *                     property="team_id",
 *                     type="array",
 *                     @OA\Items(type="string", example="The team does not belong to the user.")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Team player not found"
 *     )
 * )
 */

    public function delete(TeamPlayerUpdateDeleteRequest $request, TeamPlayer $teamPlayer)
    {
        $playerName = $teamPlayer->name;

        $team = $teamPlayer->team;

        $team->gold_remaining += $teamPlayer->playerType->cost;

        $team->save();

        $teamPlayer->delete();

        return response()->json([
            'message' => 'Player deleted successfully',
            'player' => $playerName ,
        ], 200);

    }
}
