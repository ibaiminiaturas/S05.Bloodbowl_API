<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRegistrationRequest;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
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
            return response()->json(405);
        }

        return response()->json($user, 201);

    }
}
