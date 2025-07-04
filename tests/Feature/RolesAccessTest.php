<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\Traits\UtilsForTesting;

class RolesAccessTest extends TestCase
{
    use UtilsForTesting;
    /**
     * User with Admin role can access
     */
    public function test_user_admin_can_access_to_users_table(): void
    {
        $admin = $this->getAdminUser();

        Passport::actingAs($admin);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200);
    }


    /**
     * User with NO Admin role can'T access
     */
    public function test_user_no_admin_can_not_access_to_users_table(): void
    {
        $user = $this->DeleteUserAndCreate();

        Passport::actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertStatus(403);
        $user->delete();
    }

    public function test_coach_user_can_access_coach_role_funcionalities(): void
    {
        $user = $this->DeleteUserAndCreate();

        Passport::actingAs($user);

        $response = $this->getJson('/api/skills');

        $response->assertStatus(200);
        $user->delete();
    }

    public function test_admin_user_can_access_coach_role_funcionalities(): void
    {
        $admin = $this->getAdminUser();

        Passport::actingAs($admin);

        $response = $this->getJson('/api/skills');

        $response->assertStatus(200);
    }

    public function test_user_with_no_roles_can_access_coach_role_funcionalities(): void
    {
        $user = $this->DeleteUserAndCreate(false);

        Passport::actingAs($user);

        $response = $this->getJson('/api/skills');

        $response->assertStatus(403);
        $user->delete();
    }

}
