<?php

namespace App\Http\Controllers;
use App\Models\Roster;
use App\Models\PlayerType;
use Illuminate\Http\Request;

class RosterController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/rosters",
 *     summary="Get all rosters",
 *     tags={"Rosters"},
 *     description="Returns a list of all rosters available in the system.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of rosters retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Humans")
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
        $rosters = Roster::all();
        return response()->json(['data' => $rosters]);
    }

/**
 * @OA\Get(
 *     path="/api/rosters/{roster}",
 *     summary="Get player types for a specific roster",
 *     tags={"Rosters"},
 *     description="Returns the list of player types associated with the specified roster.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="roster",
 *         in="path",
 *         description="ID of the roster",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Player types retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Lineman"),
 *                     @OA\Property(property="roster_id", type="integer", example=1),
 *                     @OA\Property(property="max_per_team", type="integer", example=16),
 *                     @OA\Property(property="movement", type="integer", example=6),
 *                     @OA\Property(property="strength", type="integer", example=3),
 *                     @OA\Property(property="agility", type="integer", example=3),
 *                     @OA\Property(property="passing", type="integer", example=2),
 *                     @OA\Property(property="armor", type="integer", example=7),
 *                     @OA\Property(property="cost", type="integer", example=50000)
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized. Invalid or missing token."
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No query results for model [App\\Models\\Roster] *"
 *     )
 * )
 */

    public function show(Roster $roster)
    {
        $rosterData = $roster->playerTypes;
        return response()->json(['data' =>$rosterData]);
    }
}
