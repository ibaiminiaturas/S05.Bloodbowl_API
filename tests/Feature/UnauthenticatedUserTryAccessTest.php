<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnauthenticatedUserTryAccessTest extends TestCase
{
    public function test_Unauthenticated_user_cannot_access_user_route()
    {
        $response = $this->getJson('/api/me');

        $response->assertStatus(401);
    }
}
