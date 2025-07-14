<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PlayerType;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/coaches",
     *     summary="Get list of users with 'coach' role",
     *     operationId="getCoaches",
     *     tags={"Coaches"},
     *     description="Returns an array of users who have the 'coach' role assigned.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Users list retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=302),
     *                     @OA\Property(property="name", type="string", example="Mrs. Melissa Franecki Jr."),
     *                     @OA\Property(property="email", type="string", format="email", example="ima.ohara@example.org"),
     *                     @OA\Property(property="email_verified_at", type="string", format="date-time", example="2025-07-08T08:05:50.000000Z"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-08T08:05:50.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-08T08:05:50.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized. Invalid or missing token."
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="User does not have the right roles."
     *     )
     * )
     */

    public function index()
    {
        //just in case
        /*$users = User::whereHas('roles', function ($q) {
            $q->where('name', 'coach')->where('guard_name', 'api');
        })->get();*/

        $users = User::role('coach')->with('teams')->get();

        return response()->json(['data' => $users]);
    }



    /**
     * @OA\Get(
     *     path="/api/coaches/{coach}",
     *     summary="Get coach data by ID",
     *     tags={"Coaches"},
     *     description="Returns basic data of a user with the 'coach' role. Returns 404 if the user does not have that role.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="coach",
     *         in="path",
     *         description="ID of the coach user",
     *         required=true,
     *         @OA\Schema(type="integer", example=302)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coach data retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=302),
     *                 @OA\Property(property="name", type="string", example="Mrs. Melissa Franecki Jr."),
     *                 @OA\Property(property="email", type="string", format="email", example="ima.ohara@example.org"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-08T08:05:50.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-08T08:05:50.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found or is not a coach"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized. Invalid or missing token."
     *     )
     * )
     */

    public function show(User $coach)
    {
        if ($coach->hasRole('coach')) {
            $coach->load('teams');
            return response()->json(['data' => [
                'id' => $coach->id,
                'name' => $coach->name,
                'email' => $coach->email,
                'created_at' => $coach->created_at,
                'updated_at' => $coach->updated_at,
                'teams' => $coach->teams]
        ]);
        } else {
            return response()->json(404);
        }
    }
}
