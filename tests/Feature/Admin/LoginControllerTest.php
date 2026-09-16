<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function loginPageCanBeRendered(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.auth.login');
    }

    #[Test]
    public function adminCanAuthenticateWithValidCredentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@klinikkeluarga.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('admin.process'), [
            'login' => 'admin@klinikkeluarga.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user, 'admin');
    }

    #[Test]
    public function adminCannotAuthenticateWithInvalidPassword(): void
    {
        User::factory()->create([
            'email' => 'admin@klinikkeluarga.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->from(route('admin.login'))->post(route('admin.process'), [
            'login' => 'admin@klinikkeluarga.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest('admin');
    }

    #[Test]
    public function authenticatedAdminCanLogout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'admin')->get(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest('admin');
    }
}
