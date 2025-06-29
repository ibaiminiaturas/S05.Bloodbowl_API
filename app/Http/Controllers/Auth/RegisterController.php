<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(request $request)
    {
        $validated = $request->validate([
                   'name' => 'required|string|max:255',
                   'email' => 'required|email|unique:users,email',
                   'password' => 'required|string|min:6|confirmed',
               ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json($user, 201);

    }
}
