<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Roster;
use App\Models\Team;
use Laravel\Passport\Passport;
use Tests\Traits\UtilsForTesting;

class TeamUpdateTest extends TestCase
{
    use UtilsForTesting;
    /**
     * A basic feature test example.
     */
    public function test_admin_user_can_update_team(): void
    {

        $admin = $this->getAdminUser();
        Passport::actingAs($admin);

        $coach = $this->DeleteUserAndCreate();

        $response = $this->postJson(
            '/api/teams',
            [
            'name' => 'Test team',
            'coach_id' => $coach->id,
            'roster_id' => Roster::first()->id,
            'gold_remaining' => 1000000,
            'team_value' => 100000
        ]
        );

        $team = Team::where('name', 'Test team')->first();

        Passport::actingAs($admin);

        $response->assertStatus(201);

        $response = $this->putJson(
            '/api/teams/'. $team->id,
            [
                'name' => 'Random name_' .  $team->id,
                'team_value' => 100011
            ]
        );

        $response->assertStatus(200);

        $team->delete();
    }

    public function test_coach_user_can_update_his_team(): void
    {
        $admin = $this->getAdminUser();
        Passport::actingAs($admin);
        $coach = $this->DeleteUserAndCreate();

        $response = $this->postJson(
            '/api/teams',
            [
            'name' => 'Test team',
            'coach_id' => $coach->id,
            'roster_id' => Roster::first()->id,
            'gold_remaining' => 1000000,
            'team_value' => 100000
        ]
        );

        $response->assertStatus(201);

        $team = Team::where('name', 'Test team')->first();

        Passport::actingAs($coach);

        $response = $this->putJson(
            '/api/teams/'. $team->id,
            [
                'name' => 'Random name_' .  $team->id,
                'team_value' => 100012
            ]
        );

        $response->assertStatus(200);

        $team->delete();
    }

    public function test_coach_user_can_not_update_a_team_is_not_theirs(): void
    {
        $admin = $this->getAdminUser();
        Passport::actingAs($admin);
        $coach = $this->DeleteUserAndCreate();

        $response = $this->postJson(
            '/api/teams',
            [
            'name' => 'Test team',
            'coach_id' => $coach->id,
            'roster_id' => Roster::first()->id,
            'gold_remaining' => 1000000,
            'team_value' => 100000
        ]
        );

        $response->assertStatus(201);

        $team = Team::where('name', 'Test team')->first();

        $coach2 = $this->DeleteUserAndCreate(true, 'another_user@gmail.com');
        Passport::actingAs($coach2);

        $response = $this->putJson(
            '/api/teams/'. $team->id,
            [
                'name' => 'Random name_' .  $team->id,
                'team_value' => 100011
            ]
        );

        $response->assertStatus(403);
        $coach2->delete();
        $coach->delete();
        $team->delete();
    }
}
