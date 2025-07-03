<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\PlayerType;
use Laravel\Passport\Passport;
use Tests\Traits\UtilsForTesting;

class RosterDataRetrieveTest extends TestCase
{
    use UtilsForTesting;
    /**
     * A basic feature test example.
     */
    public function test_user_coach_can_retrieve_one_roster_data(): void
    {
        $user = $this->DeleteUserAndCreate();

        Passport::actingAs($user);

        $response = $this->getJson('/api/rosters/1');

        $response->assertStatus(200);

        $data = $response->json();

        $this->assertNotEmpty($data);

        $playersFromRoster = PlayerType::where('roster_id', 1)->get();

        $this->assertNotEmpty($playersFromRoster);

        $this->assertEquals(count($data['data']), count($playersFromRoster));

        $user->delete();
    }
}
