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

    /**
 * @OA\Post(
 *     path="/api/teams",
 *     summary="Create a new team",
 *     tags={"Teams"},
 *     description="Creates a new team with the given data.",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "coach_id", "roster_id", "gold_remaining", "team_value"},
 *             @OA\Property(property="name", type="string", minLength=6, maxLength=255, example="Chaos Warriors"),
 *             @OA\Property(property="coach_id", type="integer", example=15),
 *             @OA\Property(property="roster_id", type="integer", example=2),
 *             @OA\Property(property="gold_remaining", type="integer", minimum=800000, maximum=30000000, example=1000000),
 *             @OA\Property(property="team_value", type="integer", minimum=0, maximum=1000000, example=850000)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Team created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=7),
 *             @OA\Property(property="name", type="string", example="Chaos Warriors"),
 *             @OA\Property(property="coach_id", type="integer", example=15),
 *             @OA\Property(property="roster_id", type="integer", example=2),
 *             @OA\Property(property="gold_remaining", type="integer", example=1000000),
 *             @OA\Property(property="team_value", type="integer", example=850000),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-08T08:05:50.000000Z"),
 *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-08T08:05:50.000000Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 example={
 *                     "name": {"The name has already been taken."},
 *                     "coach_id": {"The selected coach id is invalid."}
 *                 }
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized. Invalid or missing token."
 *     )
 * )
 */

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

    /**
 * @OA\Put(
 *     path="/api/teams/{team}",
 *     summary="Update an existing team",
 *     tags={"Teams"},
 *     description="Updates the specified team's name and team value. Only admins or the team's coach can update.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="team",
 *         in="path",
 *         description="ID of the team to update",
 *         required=true,
 *         @OA\Schema(type="integer", example=7)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "team_value"},
 *             @OA\Property(property="name", type="string", minLength=6, maxLength=255, example="Chaos Warriors Updated"),
 *             @OA\Property(property="team_value", type="integer", minimum=0, maximum=1000000, example=900000)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Team updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=7),
 *             @OA\Property(property="name", type="string", example="Chaos Warriors Updated"),
 *             @OA\Property(property="coach_id", type="integer", example=15),
 *             @OA\Property(property="roster_id", type="integer", example=2),
 *             @OA\Property(property="gold_remaining", type="integer", example=1000000),
 *             @OA\Property(property="team_value", type="integer", example=900000),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-08T08:05:50.000000Z"),
 *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-08T09:00:00.000000Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden: user does not have permission to update this team"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 example={
 *                     "name": {"The name has already been taken."},
 *                     "team_value": {"The team value must be between 0 and 1000000."}
 *                 }
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized. Invalid or missing token."
 *     )
 * )
 */

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


/**
 * @OA\Get(
 *     path="/api/teams",
 *     summary="Get list of teams with coaches and rosters",
 *     tags={"Teams"},
 *     description="Returns an array of all teams including their associated coach and roster information.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Teams list retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=7),
 *                     @OA\Property(property="name", type="string", example="Chaos Warriors"),
 *                     @OA\Property(property="coach_id", type="integer", example=15),
 *                     @OA\Property(property="roster_id", type="integer", example=2),
 *                     @OA\Property(property="gold_remaining", type="integer", example=1000000),
 *                     @OA\Property(property="team_value", type="integer", example=850000),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-08T08:05:50.000000Z"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-08T08:30:00.000000Z"),
 *                     @OA\Property(
 *                         property="coach",
 *                         type="object",
 *                         @OA\Property(property="id", type="integer", example=15),
 *                         @OA\Property(property="name", type="string", example="Coach John Doe"),
 *                         @OA\Property(property="email", type="string", format="email", example="coach.john@example.com")
 *                     ),
 *                     @OA\Property(
 *                         property="roster",
 *                         type="object",
 *                         @OA\Property(property="id", type="integer", example=2),
 *                         @OA\Property(property="name", type="string", example="Orcs")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized. Invalid or missing token."
 *     )
 * )
 */

    public function index()
    {
        $teams = Team::with(['coach', 'roster'])->get();
        return response()->json(['data' => $teams]);
    }


    /**
 * @OA\Get(
 *     path="/api/teams/{team}",
 *     summary="Get a specific team and its players",
 *     operationId="getTeamById",
 *     tags={"Teams"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="team",
 *         in="path",
 *         required=true,
 *         description="Team ID",
 *         @OA\Schema(type="integer", example=194)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Team retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=194),
 *                 @OA\Property(property="name", type="string", example="nisi"),
 *                 @OA\Property(property="coach_id", type="integer", example=400),
 *                 @OA\Property(property="roster_id", type="integer", example=4),
 *                 @OA\Property(property="gold_remaining", type="integer", example=1000000),
 *                 @OA\Property(property="team_value", type="integer", example=0),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-08T10:31:59.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-08T10:31:59.000000Z"),
 *                 @OA\Property(
 *                     property="team_players",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="id", type="integer", example=141),
 *                         @OA\Property(property="player_type_id", type="integer", example=13),
 *                         @OA\Property(property="team_id", type="integer", example=194),
 *                         @OA\Property(property="name", type="string", example="Dr. Kendrick Bergnaum"),
 *                         @OA\Property(property="player_number", type="integer", example=2),
 *                         @OA\Property(property="spp", type="integer", example=1),
 *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-08T10:32:00.000000Z"),
 *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-08T10:32:00.000000Z"),
 *                         @OA\Property(property="injuries", type="string", nullable=true, example="Consequatur aperiam sunt quos.")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="User does not have permission to view this team",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Forbidden")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Team not found"
 *     )
 * )
 */

    public function show(Team $team)
    {
        $user = User::with('roles')->find(Auth::id());

        if ($user->hasRole('admin') || ($user->hasRole('coach') && $team->coach_id == $user->id)) {
            $team->load('teamPlayers');
            return response()->json(['data' => $team], 200);
        } else {
            return response()->json(['data' => $team], 403);
        }
    }
}
