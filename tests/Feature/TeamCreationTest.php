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

class TeamCreationTest extends TestCase
{
    use UtilsForTesting;
    /**
     * A basic feature test example.
     */
    public function test_admin_user_can_create_team(): void
    {

        $admin = $this->getAdminUser();

        Passport::actingAs($admin);

        $coach = User::factory()->coach()->create();

        $response = $this->createTeam($coach->id);

        $response->assertStatus(201);

        $coach->delete();
    }


    public function test_coach_user_can_not_create_team(): void
    {
        $coach = User::factory()->coach()->create();
        Passport::actingAs($coach);

        $response = $this->createTeam($coach->id);

        $response->assertStatus(403);

        $coach->delete();
    }

    public function test_field_checks(): void
    {
        $admin = $this->getAdminUser();

        Passport::actingAs($admin);

        $response = $this->postJson(
            '/api/teams',
            [
            'coach_id' => $admin->id,
            'roster_id' => Roster::first()->id,
            'gold_remaining' => 1000000,
            'team_value' => 100000
        ]
        );

        $response->assertStatus(422);

        $response = $this->postJson(
            '/api/teams',
            [
            'name' => 'Test team',
            'roster_id' => Roster::first()->id,
            'gold_remaining' => 1000000,
            'team_value' => 100000
        ]
        );

        $response->assertStatus(422);

        $response = $this->postJson(
            '/api/teams',
            [
            'name' => 'Test team',
            'coach_id' => $admin->id,
            'gold_remaining' => 1000000,
            'team_value' => 100000
        ]
        );

        $response->assertStatus(422);

        $response = $this->postJson(
            '/api/teams',
            [
            'name' => 'Test team',
            'coach_id' => $admin->id,
            'roster_id' => Roster::first()->id,
            'team_value' => 100000
        ]
        );

        $response->assertStatus(422);

        $response = $this->postJson(
            '/api/teams',
            [
            'name' => 'Test team',
            'coach_id' => $admin->id,
            'roster_id' => Roster::first()->id,
            'gold_remaining' => 1000000,
        ]
        );

        $response->assertStatus(422);
    }

    public function test_duplicated_team_name_can_not_be_created(): void
    {
        $admin = $this->getAdminUser();

        Passport::actingAs($admin);

        $coach = User::factory()->coach()->create();

        $response = $this->createTeam($coach->id);

        $response->assertStatus(201);

        $response = $this->createTeam($coach->id);

        $response->assertStatus(422);

        $coach->delete();
    }

}
