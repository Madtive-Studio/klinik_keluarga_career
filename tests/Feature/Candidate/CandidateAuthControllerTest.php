<?php

namespace Tests\Feature\Candidate;

use App\Models\Candidate;
use App\Notifications\ActivationEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CandidateAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function candidateLoginPageCanBeRendered(): void
    {
        $response = $this->get(route('candidate.login.form'));

        $response->assertStatus(200);
        $response->assertViewIs('candidate.auth.login');
    }

    #[Test]
    public function candidateRegisterPageCanBeRendered(): void
    {
        $response = $this->get(route('candidate.register.form'));

        $response->assertStatus(200);
        $response->assertViewIs('candidate.auth.register');
    }

    #[Test]
    public function candidateCanRegisterAccountAndReceivesVerificationEmail(): void
    {
        Notification::fake();

        $response = $this->from(route('candidate.register.form'))
            ->post(route('candidate.register.verify'), [
                'name' => 'Budi Santoso',
                'username' => 'budisantoso',
                'email' => 'budi@example.com',
                'phone' => '81234567890',
                'country_code' => '+62',
                'birth_date' => '1995-05-20',
                'address' => 'Jl. Kesehatan No. 12',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertRedirect(route('candidate.register.form'));
        $this->assertDatabaseHas('candidates', [
            'email' => 'budi@example.com',
            'username' => 'budisantoso',
        ]);

        $candidate = Candidate::where('email', 'budi@example.com')->first();
        Notification::assertSentTo($candidate, ActivationEmailNotification::class);
    }

    #[Test]
    public function verifiedCandidateCanAuthenticate(): void
    {
        $candidate = Candidate::factory()->create([
            'email' => 'kandidat@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post(route('candidate.login.process'), [
            'email' => 'kandidat@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('candidate.home'));
        $this->assertAuthenticatedAs($candidate, 'candidate');
    }

    #[Test]
    public function unverifiedCandidateCannotAuthenticate(): void
    {
        Candidate::factory()->create([
            'email' => 'unverified@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => null,
        ]);

        $response = $this->from(route('candidate.login.form'))->post(route('candidate.login.process'), [
            'email' => 'unverified@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('candidate.login.form'));
        $this->assertGuest('candidate');
    }

    #[Test]
    public function authenticatedCandidateCanLogout(): void
    {
        $candidate = Candidate::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($candidate, 'candidate')->get(route('candidate.logout'));

        $response->assertRedirect(route('candidate.home'));
        $this->assertGuest('candidate');
    }
}
