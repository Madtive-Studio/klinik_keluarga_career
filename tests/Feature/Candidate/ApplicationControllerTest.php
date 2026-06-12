<?php

use App\Models\Apply;
use App\Models\Candidate;
use App\Models\Document;
use App\Models\Job;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->candidate = Candidate::factory()->create([
        'email_verified_at' => now(),
    ]);
});

it('displays applications for authenticated candidate', function () {
    $this->actingAs($this->candidate, 'candidate');

    $response = $this->get(route('candidate.my.applications.index'));

    $response->assertOk()
        ->assertViewIs('candidate.jobs.applications.index')
        ->assertViewHas('applies');
});

it('redirects guests to login on applications index', function () {
    $response = $this->get(route('candidate.my.applications.index'));

    $response->assertRedirect();
});

it('fails validation when required fields are missing', function () {
    $this->actingAs($this->candidate, 'candidate');

    $response = $this->post(route('candidate.jobs.applications.store'), []);

    $response->assertSessionHasErrors();
});

it('creates application with document upload', function () {
    Notification::fake();
    Storage::fake('public');
    $this->actingAs($this->candidate, 'candidate');

    $job = Job::factory()->create();
    $file = UploadedFile::fake()->create('cv.pdf', 500, 'application/pdf');

    $response = $this->post(route('candidate.jobs.applications.store'), [
        'job_uuid' => $job->uuid,
        'type_of_document' => 'upload',
        'cover_letter' => 'Saya tertarik dengan posisi ini.',
        'description' => 'Pengalaman saya sesuai kebutuhan.',
        'new_document' => $file,
    ]);

    $response->assertRedirect(route('candidate.jobs.applications.success', $job->uuid));

    $this->assertDatabaseHas('applies', [
        'candidate_id' => $this->candidate->id,
        'job_id' => $job->id,
        'batch_id' => $job->batch_id,
    ]);
});

it('creates application with existing document', function () {
    Notification::fake();
    $this->actingAs($this->candidate, 'candidate');

    $job = Job::factory()->create();
    $document = Document::factory()->for($this->candidate)->cv()->create();

    $response = $this->post(route('candidate.jobs.applications.store'), [
        'job_uuid' => $job->uuid,
        'type_of_document' => 'select',
        'document_id' => (string) $document->id,
        'cover_letter' => 'Cover letter.',
        'description' => 'Deskripsi.',
    ]);

    $response->assertRedirect(route('candidate.jobs.applications.success', $job->uuid));

    $this->assertDatabaseHas('applies', [
        'candidate_id' => $this->candidate->id,
        'document_id' => $document->id,
        'job_id' => $job->id,
    ]);
});

it('shows success page when application exists', function () {
    $this->actingAs($this->candidate, 'candidate');

    $job = Job::factory()->create();
    $document = Document::factory()->for($this->candidate)->cv()->create();
    Apply::factory()->forJobAndCandidate($job, $this->candidate, $document)->create();

    $response = $this->get(route('candidate.jobs.applications.success', $job->uuid));

    $response->assertOk()
        ->assertViewIs('candidate.jobs.vacancies.apply-success');
});

it('redirects success page when no application found', function () {
    $this->actingAs($this->candidate, 'candidate');

    $job = Job::factory()->create();

    $response = $this->get(route('candidate.jobs.applications.success', $job->uuid));

    $response->assertRedirect(route('candidate.jobs.vacancies.index'));
});

it('redirects guests on success page to login', function () {
    $job = Job::factory()->create();

    $response = $this->get(route('candidate.jobs.applications.success', $job->uuid));

    $response->assertRedirect(route('candidate.login.form'));
});
