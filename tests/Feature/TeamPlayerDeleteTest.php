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

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertEqualsWithDelta;

class TeamPlayerDeleteTest extends TestCase
{
    use UtilsForTesting;
    /**
     * A basic feature test example.
     */
    public function test_coach_can_delete_player_of_a_team_he_owns(): void
    {
        TeamPlayer::query()->delete();
        $response = $this->createPlayer();
        $response->assertStatus(201);

        $player = TeamPlayer::first();


        $response = $this->deleteJson(
            '/api/players/' . $player->id
        );

        $response->assertStatus(200);

    }

    public function test_coach_can_not_delete_player_of_a_team_he_does_not_own(): void
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

        $response = $this->deleteJson(
            '/api/players/' . $player->id
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

    public function test_coach_can_not_delete_player_that_does_not_exist(): void
    {
        $admin = $this->getAdminUser();
        Passport::actingAs($admin);
        $coach = User::factory()->coach()->create();
        $response = $this->createTeam($coach->id);
        Passport::actingAs($coach);

        $response = $this->deleteJson(
            '/api/players/' . 999
        );

        $response->assertStatus(404);
        $coach->delete();
    }


    public function test_gold_is_updated_correctly_after_player_is_deleted(): void
    {
        TeamPlayer::query()->delete();
        $response = $this->createPlayer();
        $response->assertStatus(201);

        $player = TeamPlayer::first();
        $previous_gold = $player->team->gold_remaining;
        $teamId = $player->team->id;
        $playerCost = $player->playerType->cost;
        $response = $this->deleteJson(
            '/api/players/' . $player->id
        );
        $team = Team::find($teamId);
        $response->assertStatus(200);

        assertEquals($previous_gold + $playerCost, $team->gold_remaining);

    }
}
