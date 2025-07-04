<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\User;
use Tests\Traits\UtilsForTesting;

class AuthenticatedUserTryAccessTest extends TestCase
{
    use UtilsForTesting;

    public function test_authenticated_user_can_access_user_route(): void
    {
        $admin = $this->getAdminUser();

        Passport::actingAs($admin);


        $response = $this->getJson('/api/user');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $admin->id,
            'email' => $admin->email,
        ]);
    }
}
