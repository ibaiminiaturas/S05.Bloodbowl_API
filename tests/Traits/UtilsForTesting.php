<?php

namespace Tests\Traits;

use App\Models\User;
use App\Models\Roster;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

trait UtilsForTesting
{
    public function DeleteUserAndCreate(bool $coachRole = true, string $email =  'ibai@example.com'): User
    {
        $user = User::firstWhere('email', $email);

        if ($user != null) {
            $user->delete();
        }

        $new_user = User::factory()->create([
            'email' => $email,
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

    public function getAdminUser(): User
    {
        return User::where('email', 'ibaiminiaturas@gmail.com')->first();
    }

    public function createTeam(int $coachId)
    {
        $response = $this->postJson(
            '/api/teams',
            [
            'name' => 'Test team',
            'coach_id' => $coachId,
            'roster_id' => Roster::first()->id,
            'gold_remaining' => 1000000,
            'team_value' => 100000
        ]
        );
        return $response;
    }

}
