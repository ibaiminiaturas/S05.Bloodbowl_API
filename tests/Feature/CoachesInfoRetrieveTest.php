<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\UtilsForTesting;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class CoachesInfoRetrieveTest extends TestCase
{
    use UtilsForTesting;
    /**
     * A basic feature test example.
     */
    public function test_admin_can_get_all_coaches(): void
    {
        $user = User::where('email', 'ibaiminiaturas@gmail.com')->first();

        Passport::actingAs($user);

        $response = $this->getJson('/api/coaches');

        $response->assertStatus(200);

        $data = $response->json();
        $this->assertNotEmpty($data);

        $this->assertEquals(count($data['data']), User::role('coach', [], 'api')->get()->count());


    }
}
