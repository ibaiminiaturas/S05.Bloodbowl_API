<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Team;
use App\Models\User;
use App\Models\TeamPlayer;
use Tests\Traits\UtilsForTesting;
use Laravel\Passport\Passport;

class MatchSimulationTest extends TestCase
{
    use UtilsForTesting;
    public function test_simulate_match_with_valid_teams(): void
    {
        TeamPlayer::query()->delete();
        Team::query()->delete();
        $teamA = Team::factory()->create();
        $teamB = Team::factory()->create();

        $admin = $this->getAdminUser();


        TeamPlayer::factory()
            ->count(10)
            ->forTeamRoster($teamA->id)
            ->create();
        TeamPlayer::factory()
            ->count(10)
            ->forTeamRoster($teamB->id)
            ->create();

        Passport::actingAs($admin);

        $response =  $this->postJson('/api/matches/simulate', [
               'team_1_id' => $teamA->id,
               'team_2_id' => $teamB->id,
           ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
    'winner',
    'team_1',
    'team_2',

]);

    }

    public function test_simulate_match_fails_if_teams_do_not_exist()
    {

        TeamPlayer::query()->delete();
        Team::query()->delete();
        $admin = $this->getAdminUser();
        $team = Team::factory()->create();
        Passport::actingAs($admin);


        $response = $this->postJson('/api/matches/simulate', [
            'team_1_id' => 9999,
            'team_2_id' => $team->id,
        ]);
        $response->assertStatus(422);


        $response = $this->postJson('/api/matches/simulate', [
            'team_1_id' => $team->id,
            'team_2_id' => 8888,
        ]);
        $response->assertStatus(422);
    }

}
