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


class TeamPlayerCreationTest extends TestCase
{
    use UtilsForTesting;

    public function test_admin_can_create_player_into_team(): void
    {
        $this->withoutExceptionHandling();
        $admin = $this->getAdminUser();
        Passport::actingAs($admin);
        $coach = $this->DeleteUserAndCreate();
        $response = $this->createTeam($coach->id);
        $team = Team::where('name', 'Test team')->first();

        $response = $this->postJson('/api/teams/'. $team->id . '/players');

        $response->assertStatus(200);
    }
}
