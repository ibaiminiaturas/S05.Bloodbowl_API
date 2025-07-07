<?php

namespace Tests\Traits;

use App\Models\User;
use App\Models\Roster;
use App\Models\Team;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Laravel\Passport\Passport;

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

    public function createTeam(int $coachId, string $team_name = 'Test team')
    {
        $response = $this->postJson(
            '/api/teams',
            [
            'name' => $team_name,
            'coach_id' => $coachId,
            'roster_id' => Roster::first()->id,
            'gold_remaining' => 1000000,
            'team_value' => 100000
        ]
        );
        return $response;
    }

    private function createPlayer(bool $coachCreatesPlayer = false, bool $rosterNew = false)
    {
        $admin = $this->getAdminUser();
        Passport::actingAs($admin);
        $coach = $this->DeleteUserAndCreate();
        $response = $this->createTeam($coach->id);
        $team = Team::where('name', 'Test team')->first();

        if ($coachCreatesPlayer) {
            Passport::actingAs($coach);
        }

        if ($rosterNew) {
            $playerType = 33;
        } else {
            $playerType = 1;
        }

        $response = $this->postJson(
            '/api/teams/'. $team->id . '/players',
            [
            'name' => 'Test Player',
            'player_type_id' => $playerType,
            'player_number' => 1,
            'injuries' => '',
            'spp' => 2

        ]
        );

        return $response;
    }

}
