<?php

namespace Tests\Feature\Candidate;

use App\Models\Candidate;
use App\Models\CandidateProfile;
use App\Models\Job;
use App\Models\Apply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VacancyControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function indexDisplaysVacancyList(): void
    {
        Job::factory()->count(2)->create();

        $response = $this->get(route('candidate.jobs.vacancies.index'));

        $response->assertStatus(200);
        $response->assertViewIs('candidate.jobs.vacancies.index');
        $response->assertViewHas('jobs');
    }

    #[Test]
    public function showDisplaysJobDetail(): void
    {
        $job = Job::factory()->create();

        $response = $this->get(route('candidate.jobs.vacancies.show', $job->uuid));

        $response->assertStatus(200);
        $response->assertViewIs('candidate.jobs.vacancies.detail');
        $response->assertViewHas('job');
    }

    #[Test]
    public function showReturns404ForUnknownUuid(): void
    {
        $response = $this->get(route('candidate.jobs.vacancies.show', '00000000-0000-0000-0000-000000000000'));

        $response->assertNotFound();
    }

    #[Test]
    public function applyRedirectsGuestsToLogin(): void
    {
        $job = Job::factory()->create();

        $response = $this->get(route('candidate.jobs.vacancies.apply', $job->uuid));

        $response->assertRedirect();
    }

    #[Test]
    public function applyShowsFormWhenAuthenticatedAndNotYetApplied(): void
    {
        $candidate = Candidate::factory()->create([
            'email_verified_at' => now(),
        ]);
        CandidateProfile::factory()->for($candidate)->create([
            'education_level' => 'SMA',
        ]);
        $job = Job::factory()->create();

        $this->actingAs($candidate, 'candidate');

        $response = $this->get(route('candidate.jobs.vacancies.apply', $job->uuid));

        $response->assertStatus(200);
        $response->assertViewIs('candidate.jobs.vacancies.apply');
        $response->assertViewHas('job');
    }

    #[Test]
    public function applyRedirectsWhenAlreadyApplied(): void
    {
        $candidate = Candidate::factory()->create([
            'email_verified_at' => now(),
        ]);
        $job = Job::factory()->create();

        Apply::factory()->forJobAndCandidate(
            $job,
            $candidate,
            \App\Models\Document::factory()->for($candidate)->cv()->create()
        )->create();

        $this->actingAs($candidate, 'candidate');

        $response = $this->get(route('candidate.jobs.vacancies.apply', $job->uuid));

        $response->assertRedirect(route('candidate.jobs.vacancies.show', $job->uuid));
    }
}
