<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/api/skills",
 *     summary="Get all skills",
 *     tags={"Skills"},
 *     description="Returns a list of all available skills.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of skills retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Block"),
 *                     @OA\Property(property="description", type="string", example="Allows a player to safely block an opponent.")
 *                 )
 *             ),
 *             example={
 *                 "data": {
 *                     {
 *                         "id": 1,
 *                         "name": "Block",
 *                         "description": "Allows a player to safely block an opponent."
 *                     },
 *                     {
 *                         "id": 2,
 *                         "name": "Dodge",
 *                         "description": "Helps a player avoid tackles more easily."
 *                     },
 *                     {
 *                         "id": 3,
 *                         "name": "Mighty Blow",
 *                         "description": "Increases damage on blocks."
 *                     },
 *                     {
 *                         "id": 4,
 *                         "name": "Kick",
 *                         "description": "Improves kicking distance."
 *                     },
 *                     {
 *                         "id": 5,
 *                         "name": "Catch",
 *                         "description": "Increases chance of catching the ball."
 *                     },
 *                 }
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized. Invalid or missing token."
 *     )
 * )
 */

class SkillController extends Controller
{

    public function index()
    {
        $skills = Skill::all();
        return response()->json(['data' => $skills]);
    }
}
