<?php

namespace Tests\Feature\Candidate;

use App\Enums\EducationLevel;
use App\Models\Candidate;
use App\Models\CandidateProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Candidate $candidate;

    protected function setUp(): void
    {
        parent::setUp();
        $this->candidate = Candidate::factory()->create([
            'email_verified_at' => now(),
        ]);
    }

    #[Test]
    public function guestIsRedirectedToLogin(): void
    {
        $response = $this->get(route('candidate.my.profile.edit'));
        $response->assertRedirect();
    }

    #[Test]
    public function candidateCanViewProfileEditPage(): void
    {
        $response = $this->actingAs($this->candidate, 'candidate')
            ->get(route('candidate.my.profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('candidate.profile.edit');
        $response->assertViewHas(['candidate', 'educationLevels']);
    }

    #[Test]
    public function candidateCanUpdateProfileData(): void
    {
        $response = $this->actingAs($this->candidate, 'candidate')
            ->put(route('candidate.my.profile.update'), [
                'name' => 'Dr. Rina Wijaya',
                'username' => 'dr_rina',
                'phone' => '081234567891',
                'education_level' => EducationLevel::S1->value,
                'major' => 'Kedokteran Umum',
                'university' => 'Universitas Indonesia',
                'gpa' => 3.75,
                'years_of_experience' => 3,
                'last_position' => 'Dokter Umum',
                'last_company' => 'RS Harapan Bunda',
                'city' => 'Cianjur',
                'province' => 'Jawa Barat',
                'expected_salary' => 8000000,
                'skills' => ['ACLS', 'ATLS', 'Pelayanan Gawat Darurat'],
            ]);

        $response->assertRedirect(route('candidate.my.profile.edit'));
        $this->assertDatabaseHas('candidates', [
            'id' => $this->candidate->id,
            'name' => 'Dr. Rina Wijaya',
            'username' => 'dr_rina',
        ]);

        $this->assertDatabaseHas('candidate_profiles', [
            'candidate_id' => $this->candidate->id,
            'major' => 'Kedokteran Umum',
            'university' => 'Universitas Indonesia',
        ]);
    }
}
