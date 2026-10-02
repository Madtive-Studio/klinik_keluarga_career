<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'username' => 'admin_test',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    #[Test]
    public function guestIsRedirectedToAdminLogin(): void
    {
        $response = $this->get(route('admin.profile.edit'));
        $response->assertRedirect(route('admin.login'));
    }

    #[Test]
    public function editDisplaysProfileFormWithoutLevelField(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.profile.edit');
        $response->assertViewHas('user');
        $response->assertSee($this->admin->name);
        $response->assertSee($this->admin->email);
        $response->assertSee($this->admin->username);
        // Ensure Level field is not displayed
        $response->assertDontSee(__('admin.profile.level'));
    }

    #[Test]
    public function updateModifiesAdminProfileData(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => 'Updated Admin Name',
                'username' => 'updated_admin',
                'email' => 'updated_admin@example.com',
            ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success', __('messages.admin.profile.updated'));

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'name' => 'Updated Admin Name',
            'username' => 'updated_admin',
            'email' => 'updated_admin@example.com',
        ]);
    }

    #[Test]
    public function updateCanChangePasswordWithValidCurrentPassword(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => $this->admin->name,
                'username' => $this->admin->username,
                'email' => $this->admin->email,
                'current_password' => 'password123',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success', __('messages.admin.profile.updated'));

        $this->assertTrue(Hash::check('newpassword123', $this->admin->fresh()->password));
    }

    #[Test]
    public function updateFailsWhenCurrentPasswordIsInvalid(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => $this->admin->name,
                'username' => $this->admin->username,
                'email' => $this->admin->email,
                'current_password' => 'wrongpassword',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertSessionHasErrors(['current_password']);
    }
}
