<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRegistrationRequest;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registro de usuario.",
     *     operationId="registerUser",
     *     tags={"Authoritation"},
     *     description="Crea un nuevo usuario en el sistema con los datos proporcionados. Devuelve información básica del usuario creado y un token de acceso para autenticación inmediata. Además agrega el rol 'coach' al usuario creado",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="nombre1"),
     *             @OA\Property(property="email", type="string", format="email", example="usuario@ejemplo.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registro exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="nombre1"),
     *             @OA\Property(property="email", type="string", format="email", example="usuario@ejemplo.com"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-08T08:47:41.000000Z"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-08T08:47:41.000000Z"),
     *             @OA\Property(property="id", type="integer", example=345)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Errores de validación",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "email": {
     *                         "The email has already been taken.",
     *                         "The email field is required."
     *                     },
     *                     "name": {
     *                         "The name field is required."
     *                     },
     *                     "password": {
     *                         "The password field is required.",
     *                         "The password field confirmation does not match.",
     *                         "The password field must be at least 6 characters."
     *                     }
     *                 }
     *             )
     *         )
     *     )
     * )
     */


    public function register(UserRegistrationRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $role = Role::firstOrCreate(
            ['name' => 'coach', 'guard_name' => 'api']
        );

        if ($role) {
            $user->assignRole($role);
        } else {
            return response()->json(['error' => 'Role creation failed'], 405);
        }

        // Aquí se genera el token directamente con Passport
        $tokenResult = $user->createToken('auth_token');
        $token = $tokenResult->accessToken;
        $expiresAt = $tokenResult->token->expires_at;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $expiresAt,
            'user' => $user,
        ], 201);
    }
}
