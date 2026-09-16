<?php

namespace Tests\Feature\Candidate;

use App\Models\Candidate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_verification_token_verifies_email_and_shows_success_view()
    {
        $candidate = Candidate::factory()->create([
            'email_verified_at' => null,
            'verification_token' => 'valid-test-token-12345',
        ]);

        $response = $this->get(route('candidate.email-verification', [
            'token' => 'valid-test-token-12345',
            'email' => $candidate->email,
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('candidate.auth.success-verification');

        $candidate->refresh();
        $this->assertNotNull($candidate->email_verified_at);
    }

    public function test_already_verified_token_redirects_to_login_with_info_message()
    {
        $candidate = Candidate::factory()->create([
            'email_verified_at' => now(),
            'verification_token' => 'already-verified-token-12345',
        ]);

        $response = $this->get(route('candidate.email-verification', [
            'token' => 'already-verified-token-12345',
            'email' => $candidate->email,
        ]));

        $response->assertRedirect(route('candidate.login.form'));
        $response->assertSessionHas('info', __('messages.auth.already_verified'));
    }

    public function test_invalid_verification_token_redirects_to_login_with_error_message()
    {
        $response = $this->get(route('candidate.email-verification', [
            'token' => 'completely-fake-token',
        ]));

        $response->assertRedirect(route('candidate.login.form'));
        $response->assertSessionHas('error', __('messages.auth.invalid_verification_token'));
    }
}
