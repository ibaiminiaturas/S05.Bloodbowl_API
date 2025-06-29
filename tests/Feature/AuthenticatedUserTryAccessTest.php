<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;

class AuthenticatedUserTryAccessTest extends TestCase
{
    public function test_authorized_user_can_access_user_route()
    {
        // Creamos un usuario (puedes usar factory)
        $user = User::factory()->create();

        // Autenticamos al usuario usando Passport
        Passport::actingAs($user);

        // Hacemos petición a ruta protegida
        $response = $this->getJson('/api/user');

        // Debe devolver 200 y los datos del usuario
        $response->assertStatus(200)
                 ->assertJson([
                    'id' => $user->id,
                    'email' => $user->email,
                 ]);
    }
}
