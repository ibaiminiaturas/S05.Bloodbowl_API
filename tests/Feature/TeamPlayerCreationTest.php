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

    public function test_admin_can_create_player_into_team(): void
    {
        $admin = $this->getAdminUser();
        Passport::actingAs($admin);
        $coach = $this->DeleteUserAndCreate();
        $response = $this->createTeam($coach->id);
        $team = Team::where('name', 'Test team')->first();

        $response = $this->postJson('/api/teams/'. $team->id . '/players',
        [
            'name' => 'Test Player',
            'team_id' => $team->id,
            'player_type_id' => PlayerType::where('roster_id', $team->roster_id)->first()->id,
            'player_number' => 1,
            'injuries' => '',
            'spp' => 2
        
        ]);

        $response->assertStatus(201);
        $player = TeamPlayer::where('name', 'Test Player')->delete();
        $response->assertStatus(201);
        $team->delete();
    }


    public function test_admin_can_not_create_player_into_team_from_different_roster(): void
    {
        $admin = $this->getAdminUser();
        Passport::actingAs($admin);
        $coach = $this->DeleteUserAndCreate();
        $response = $this->createTeam($coach->id);
        $team = Team::where('name', 'Test team')->first();

        $response = $this->postJson('/api/teams/'. $team->id . '/players',
        [
            'name' => 'Test Player',
            'team_id' => $team->id,
            'player_type_id' => PlayerType::where('roster_id', 3)->first()->id,
            'player_number' => 1,
            'injuries' => '',
            'spp' => 2
        
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('player_type_id');
        $response->assertJsonFragment([
            'player_type_id' => ['The type of player does not belong to the team roster.'],
        ]);
        $coach->delete();
    }

    public function test_coach_can_create_player_into_team(): void
    {
        $admin = $this->getAdminUser();
        Passport::actingAs($admin);
        $coach = $this->DeleteUserAndCreate();
        $response = $this->createTeam($coach->id);
        $team = Team::where('name', 'Test team')->first();

        $response = $this->postJson('/api/teams/'. $team->id . '/players',
        [
            'name' => 'Test Player',
            'team_id' => $team->id,
            'player_type_id' => PlayerType::where('roster_id', $team->roster_id)->first()->id,
            'player_number' => 1,
            'injuries' => '',
            'spp' => 2
        
        ]);

        $response->assertStatus(201);
        $coach->delete();
    }



}
