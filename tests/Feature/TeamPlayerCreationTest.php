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

class TeamPlayerCreationTest extends TestCase
{
    use UtilsForTesting;

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


    public function test_admin_can_create_player_into_team(): void
    {
        $response = $this->createPlayer();
        $response->assertStatus(201);
        $player = TeamPlayer::where('name', 'Test Player')->delete();
        $response->assertStatus(201);


    }


    public function test_admin_can_not_create_player_into_team_from_different_roster(): void
    {
        $response = $this->createPlayer(false, true);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('player_type_id');
        $response->assertJsonFragment([
            'player_type_id' => ['The type of player does not belong to the team roster.'],
        ]);

    }

    public function test_coach_can_create_player_into_team(): void
    {
        $response = $this->createPlayer(true);
        $response->assertStatus(201);

    }

    public function test_coach_can_not_create_player_into_team_from_different_roster(): void
    {

        $response = $this->createPlayer(false, true);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('player_type_id');
        $response->assertJsonFragment([
            'player_type_id' => ['The type of player does not belong to the team roster.'],
        ]);

    }

    public function test_coach_can_not_create_player_with_same_name(): void
    {
        $response = $this->createPlayer(true);
        $response->assertStatus(201);
        $team = Team::where('name', 'Test team')->first();
        $response = $this->postJson(
            '/api/teams/'. $team->id . '/players',
            [
            'name' => 'Test Player',
            'player_type_id' => $team->roster_id,
            'player_number' => 2,
            'injuries' => '',
            'spp' => 2

        ]
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
        $response->assertJsonFragment([
            'errors' => [
                'name' => ['The name has already been taken.'],
            ],
        ]);

    }

    public function test_coach_can_not_create_player_with_same_number(): void
    {
        $response = $this->createPlayer(true);
        $response->assertStatus(201);
        $team = Team::where('name', 'Test team')->first();
        $response = $this->postJson(
            '/api/teams/'. $team->id . '/players',
            [
            'name' => 'Test Player 2',
            'player_type_id' => PlayerType::where('roster_id', $team->roster_id)->first()->id,
            'player_number' => 1,
            'injuries' => '',
            'spp' => 2

        ]
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('player_number');
        $response->assertJsonFragment([
            'errors' => [
                'player_number' => ['The player number has already been taken.'],
            ],
        ]);


    }

    public function test_coach_can_not_create_player_into_team_that_is_not_theirs(): void
    {

        $admin = $this->getAdminUser();
        Passport::actingAs($admin);
        $coach = $this->DeleteUserAndCreate(true);
        $response = $this->createTeam($coach->id);

        $coach_2 = $this->DeleteUserAndCreate(true, 'another_email@gmail.com');
        $response = $this->createTeam($coach_2->id, 'another team');
        $team_2 = Team::where('name', 'another team')->first();

        Passport::actingAs($coach);

        $response = $this->postJson(
            '/api/teams/'. $team_2->id . '/players',
            [
            'name' => 'Test Player',
            'player_type_id' => 1,
            'player_number' => 1,
            'injuries' => '',
            'spp' => 2
        ]
        );

        $response->assertJsonValidationErrors('team_id');
        $response->assertJsonFragment([
            'errors' => [
                'team_id' => ['The team does not belong to the user.'],
            ],
        ]);
    }

    public function test_coach_can_not_create_player_because_has_not_enough_gold(): void
    {

    }

    public function test_coach_can_not_create_player_because_has_not_enough_spots_for_taht_player_type(): void
    {

    }

}
