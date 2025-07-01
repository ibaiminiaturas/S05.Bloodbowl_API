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
