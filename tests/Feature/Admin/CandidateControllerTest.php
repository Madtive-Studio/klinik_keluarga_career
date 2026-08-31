<?php

namespace Tests\Feature\Admin;

use App\Models\Candidate;
use App\Models\CandidateProfile;
use App\Models\CandidateSkill;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get(route('admin.candidates.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_index_displays_candidates_table(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.candidates.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.candidates.index');
    }

    public function test_show_displays_candidate_detail_dossier(): void
    {
        $candidate = Candidate::factory()->create([
            'name' => 'Dokter Budi Santoso',
            'email' => 'budi@klinikkeluarga.com',
            'phone' => '081234567890',
        ]);

        CandidateProfile::create([
            'candidate_id' => $candidate->id,
            'education_level' => 'S1',
            'major' => 'Pendidikan Dokter',
            'university' => 'Universitas Indonesia',
            'gpa' => '3.85',
            'years_of_experience' => 3,
            'last_position' => 'Dokter Umum',
            'last_company' => 'RS Harapan Sehat',
            'city' => 'Cianjur',
            'province' => 'Jawa Barat',
            'expected_salary' => 8000000,
        ]);

        CandidateSkill::create([
            'candidate_id' => $candidate->id,
            'name' => 'Penanganan Gawat Darurat',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.candidates.show', $candidate->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.candidates.detail');
        $response->assertSee('Dokter Budi Santoso');
        $response->assertSee('Pendidikan Dokter');
        $response->assertSee('Universitas Indonesia');
        $response->assertSee('Penanganan Gawat Darurat');
    }
}
