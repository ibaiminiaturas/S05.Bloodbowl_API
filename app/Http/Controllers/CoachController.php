<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PlayerType;
use Illuminate\Http\Request;
use App\Http\Requests\UserDeleteRequest;

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
        $perPage = config('PAGINATE_PER_PAGE');

        $users = User::role('coach')->with('teams')->paginate($perPage);

        return response()->json(['data' => $users]);
    }

    /**
     * @OA\Delete(
     *     path="/api/coaches/{coach}",
     *     summary="Eliminar un coach",
     *     description="Elimina un coach por ID. Solo accesible para usuarios con rol admin. También elimina sus equipos y jugadores asociados.",
     *     operationId="deleteCoach",
     *     tags={"Coaches"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="coach",
     *         in="path",
     *         description="ID del coach a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="coach@example.com")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Coach eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Coach deleted successfully"),
     *             @OA\Property(property="coach", type="string", example="Ibai")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="El usuario no es un coach o el email es inválido",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not a coach")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="There is no coach registered with that email.")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado"
     *     )
     * )
     */
    public function delete(Request $request, User $coach)
    {
        $coach->delete();

        return response()->json([
            'message' => 'Coach deleted successfully',
            'coach' => $coach->name ,
        ], 200);

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
