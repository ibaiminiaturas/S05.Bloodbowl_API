<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Roster;
use Laravel\Passport\Passport;
use Tests\Traits\UtilsForTesting;

class TeamCreationTest extends TestCase
{
    use UtilsForTesting;
    /**
     * A basic feature test example.
     */
    public function test_admin_useer_can_create_team(): void
    {
        $this->withoutExceptionHandling();
        $user = User::where('email', 'ibaiminiaturas@gmail.com')->first();

        Passport::actingAs($user);

        $coach = $this->DeleteUserAndCreate();

        $response = $this->postJson(
            '/api/teams',
            [
            'name' => 'Test team',
            'coach_id' => $coach->id,
            'rooter_id' => Roster::first()->id,
            'gold_remaining' => 1000000,
            'team_value' => 100000
        ]
        );

        $response->assertStatus(200);
    }
}
