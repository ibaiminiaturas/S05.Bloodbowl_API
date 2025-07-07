<?php

namespace Tests\Feature;

//app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
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
        $admin = $this->getAdminUser();
        Passport::actingAs($admin);

        $response = $this->getJson('/api/coaches');

        $response->assertStatus(200);

        $data = $response->json();
        $this->assertNotEmpty($data);
        //codigo por si acaso que menuda liada
        //$coachesAmount = User::with('roles')->get()->filter(
        //   fn ($user) => $user->roles->where('name', 'coach')->toArray()
        //)->count();
        $users = User::role('coach')->get();
        $this->assertEquals(count($data['data']), $users->count());

    }


    public function test_no_admin_user_can_not_get_all_coaches(): void
    {
        $coach = User::factory()->create();

        Passport::actingAs($coach);

        $response = $this->getJson('/api/coaches');

        $response->assertStatus(403);

        $coach->delete();
    }

    public function test_admin_user_can_get_one_coach(): void
    {
        $admin = $this->getAdminUser();

        Passport::actingAs($admin);

        $coach = User::factory()->create();

        $response = $this->getJson('/api/coaches/'.$coach->id);

        $response->assertStatus(200);

        $coach->delete();

    }
}
