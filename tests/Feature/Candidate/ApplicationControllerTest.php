<?php

namespace Tests\Feature\Candidate;

use App\Models\Apply;
use App\Models\Candidate;
use App\Models\CandidateProfile;
use App\Models\Document;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApplicationControllerTest extends TestCase
{
    use RefreshDatabase;

    private Candidate $candidate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->candidate = Candidate::factory()->create([
            'email_verified_at' => now(),
        ]);

        CandidateProfile::factory()->for($this->candidate)->create([
            'education_level' => 'S1',
            'years_of_experience' => 2,
        ]);
    }

    #[Test]
    public function indexDisplaysApplicationsForAuthenticatedCandidate(): void
    {
        $this->actingAs($this->candidate, 'candidate');

        $response = $this->get(route('candidate.my.applications.index'));

        $response->assertStatus(200);
        $response->assertViewIs('candidate.jobs.applications.index');
        $response->assertViewHas('applies');
    }

    #[Test]
    public function indexRedirectsGuestsToLogin(): void
    {
        $response = $this->get(route('candidate.my.applications.index'));

        $response->assertRedirect();
    }

    #[Test]
    public function storeValidationFailsWhenFieldsMissing(): void
    {
        $this->actingAs($this->candidate, 'candidate');

        $response = $this->post(route('candidate.jobs.applications.store'), []);

        $response->assertSessionHasErrors();
    }

    #[Test]
    public function storeCreatesApplicationWithDocumentUpload(): void
    {
        Notification::fake();
        Storage::fake('public');

        $this->actingAs($this->candidate, 'candidate');

        $job = Job::factory()->create();
        $file = UploadedFile::fake()->create('cv.pdf', 500, 'application/pdf');

        $response = $this->post(route('candidate.jobs.applications.store'), [
            'job_uuid'         => $job->uuid,
            'type_of_document' => 'upload',
            'cover_letter'     => 'Saya tertarik dengan posisi ini.',
            'description'      => 'Pengalaman saya sesuai kebutuhan.',
            'new_document'     => $file,
        ]);

        $response->assertRedirect(route('candidate.jobs.applications.success', $job->uuid));

        $this->assertDatabaseHas('applies', [
            'candidate_id' => $this->candidate->id,
            'job_id'       => $job->id,
            'batch_id'     => $job->batch_id,
        ]);

        $apply = Apply::where('candidate_id', $this->candidate->id)->where('job_id', $job->id)->first();
        $this->assertNotNull($apply->auto_score);
        $this->assertNotNull($apply->score_recommendation);
    }

    #[Test]
    public function storeCreatesApplicationWithExistingDocument(): void
    {
        Notification::fake();

        $this->actingAs($this->candidate, 'candidate');

        $job = Job::factory()->create();
        $document = Document::factory()->for($this->candidate)->cv()->create();

        $response = $this->post(route('candidate.jobs.applications.store'), [
            'job_uuid'         => $job->uuid,
            'type_of_document' => 'select',
            'document_id'      => (string) $document->id,
            'cover_letter'     => 'Cover letter.',
            'description'      => 'Deskripsi.',
        ]);

        $response->assertRedirect(route('candidate.jobs.applications.success', $job->uuid));

        $this->assertDatabaseHas('applies', [
            'candidate_id' => $this->candidate->id,
            'document_id'  => $document->id,
            'job_id'       => $job->id,
        ]);
    }

    #[Test]
    public function applySuccessShowsPageWhenApplicationExists(): void
    {
        $this->actingAs($this->candidate, 'candidate');

        $job = Job::factory()->create();
        $document = Document::factory()->for($this->candidate)->cv()->create();

        Apply::factory()->forJobAndCandidate($job, $this->candidate, $document)->create();

        $response = $this->get(route('candidate.jobs.applications.success', $job->uuid));

        $response->assertStatus(200);
        $response->assertViewIs('candidate.jobs.vacancies.apply-success');
    }

    #[Test]
    public function applySuccessRedirectsWhenNoApplication(): void
    {
        $this->actingAs($this->candidate, 'candidate');

        $job = Job::factory()->create();

        $response = $this->get(route('candidate.jobs.applications.success', $job->uuid));

        $response->assertRedirect(route('candidate.jobs.vacancies.index'));
    }

    #[Test]
    public function applySuccessRedirectsGuestsToLogin(): void
    {
        $job = Job::factory()->create();

        $response = $this->get(route('candidate.jobs.applications.success', $job->uuid));

        $response->assertRedirect(route('candidate.login.form'));
    }
}
