<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Team;
use App\Models\Roster;
use Laravel\Passport\Passport;
use Tests\Traits\UtilsForTesting;

class TeamsRetrieveTest extends TestCase
{
    use UtilsForTesting;
    /**
     * A basic feature test example.
     */
    public function test_admin_can_retrieve_all_teams(): void
    {
        $admin = $this->getAdminUser();
        Passport::actingAs($admin);

        $response = $this->getJson('/api/teams');

        $response->assertStatus(200);

        $data = $response->json('data');

        $this->assertEquals(count($data['data']), Team::all()->count());

    }

    public function test_coach_can_retrieve_only_his_teams(): void
    {
        $coach = User::factory()->coach()->create();

        Passport::actingAs($coach);

        $response = $this->getJson('/api/teams');

        $response->assertStatus(200);
        $coach->delete();
    }

    public function test_admin_can_retrieve_one_team(): void
    {
        $admin = $this->getAdminUser();
        Passport::actingAs($admin);
        $coach = User::factory()->coach()->create();

        $response = $this->createTeam($coach->id);

        $response->assertStatus(201);

        $team = Team::where('name', 'Test team')->first();

        $response = $this->getJson('/api/teams/'. $team->id);

        $response->assertStatus(200);

        $data = $response->json('data');

        $this->assertNotEmpty($data);

        $coach->delete();
    }

    public function test_coach_can_retrieve_one_team_of_theirs(): void
    {
        $admin = $this->getAdminUser();
        Passport::actingAs($admin);
        $coach = User::factory()->coach()->create();

        $response = $this->createTeam($coach->id);

        $response->assertStatus(201);

        $team = Team::where('name', 'Test team')->first();

        $response = $this->getJson('/api/teams/'. $team->id);

        $response->assertStatus(200);

        $data = $response->json('data');

        $this->assertNotEmpty($data);

        $coach->delete();
    }


    public function test_coach_can_not_retrieve_one_team_not_of_theirs(): void
    {
        $admin = $this->getAdminUser();
        Passport::actingAs($admin);
        $coach = User::factory()->coach()->create();

        $response = $this->createTeam($coach->id);

        $response->assertStatus(201);

        $team = Team::where('name', 'Test team')->first();

        $coachNotOfTheTeam = User::factory()->coach()->create();
        Passport::actingAs($coachNotOfTheTeam);

        $response = $this->getJson('/api/teams/'. $team->id);

        $response->assertStatus(403);

        $coachNotOfTheTeam->delete();
        $coach->delete();
    }

}
