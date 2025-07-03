<?php

namespace Tests\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

trait UtilsForTesting
{
    public function DeleteUserAndCreate(bool $coachRole = true): User
    {
        $user = User::firstWhere('email', 'ibai@example.com');

        if ($user != null) {
            $user->delete();
        }

        $new_user = User::factory()->create([
            'email' => 'ibai@example.com',
            'password' => Hash::make('secret123'),
        ]);

        if ($coachRole) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions(); //porque si no la lia pardisima el spatie.
            $role = Role::firstOrCreate(
                ['name' => 'coach', 'guard_name' => 'api']
            );
            $new_user->assignRole($role);
        }
        return $new_user;

    }

}
