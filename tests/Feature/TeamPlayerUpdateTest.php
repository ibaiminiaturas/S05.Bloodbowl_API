<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Team;
use App\Models\Roster;
use App\Models\PlayerType;
use App\Models\TeamPlayer;
use Laravel\Passport\Passport;
use Tests\Traits\UtilsForTesting;

class TeamPlayerUpdateTest extends TestCase
{
    use UtilsForTesting;
    /**
     * A basic feature test example.
     */
    public function test_coach_can_edit_player_of_a_team_he_owns(): void
    {
        TeamPlayer::query()->delete();
        $response = $this->createPlayer();
        $response->assertStatus(201);

        $player = TeamPlayer::first();


        $response = $this->putJson(
            '/api/players/' . $player->id,
            [
            'injuries' => 'Death',
            'spp' => 19
        ]
        );

        $response->assertStatus(200);

    }

    public function test_coach_can_not_edit_player_of_a_team_he_does_not_own(): void
    {
        TeamPlayer::query()->delete();
        $response = $this->createPlayer();
        $response->assertStatus(201);
        $player = TeamPlayer::first();



        $admin = $this->getAdminUser();
        Passport::actingAs($admin);
        $coach = User::factory()->coach()->create();
        $response = $this->createTeam($coach->id, 'another team');
        $team = Team::where('name', 'another team')->first();

        Passport::actingAs($coach);

        $response = $this->putJson(
            '/api/players/' . $player->id,
            [
            'injuries' => 'Death',
            'spp' => 19
        ]
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('team_id');
        $response->assertJsonFragment([
            'errors' => [
                'team_id' => ['The team does not belong to the user.'],
            ],
        ]);

        $coach->delete();

    }
}
