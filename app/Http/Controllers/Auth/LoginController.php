<?php

namespace App\Http\Controllers\Auth;

use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{


     /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Iniciar sesión",
     *     tags={"Authoritation"},
     *     description="Autentica un usuario y devuelve un token Bearer.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="usuario@ejemplo.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Credenciales inválidas"
     *     )
     * )
     */
    public function login(UserLoginRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();


        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => [
                         'email' => ['Invalid credentials.']
                        ]
            ], 422);
        }

        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);

    }


   
     /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Cerrar sesión",
     *     tags={"Authoritation"},
     *     description="Cierra la sesión del usuario invalidando el token de acceso actual. El usuario deberá autenticarse nuevamente para obtener un nuevo token.",
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated."
     *     )
     * )
     */ 
    public function logout(Request $request)
    {
        $token = $request->user()->token();

        $token->revoke();

        $token->refresh();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
