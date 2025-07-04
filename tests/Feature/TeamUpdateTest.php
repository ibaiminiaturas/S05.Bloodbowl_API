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

        $user = User::where('email', 'ibaiminiaturas@gmail.com')->first();

        Passport::actingAs($user);

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

        $response->assertStatus(201);

        $response = $this->putJson(
            '/api/teams/'. $team->id,
            [
                'gold_remaining' => 1000011,
                'team_value' => 100011
            ]
        );

        $team->delete();
    }

    public function test_coach_user_can_update_his_team(): void
    {
        $user = User::where('email', 'ibaiminiaturas@gmail.com')->first();
        Passport::actingAs($user);
        $coach = $this->DeleteUserAndCreate();
        //Passport::actingAs($coach);

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
                'gold_remaining' => 1000011,
                'team_value' => 100011
            ]
        );

        //$team->delete();
    }
}
