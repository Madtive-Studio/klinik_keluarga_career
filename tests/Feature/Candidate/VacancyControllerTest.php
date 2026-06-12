<?php

use App\Models\Batch;
use App\Models\Apply;
use App\Models\Candidate;
use App\Models\Document;
use App\Models\Job;

it('displays vacancy list', function () {
    Job::factory()->count(2)->create();

    $response = $this->get(route('candidate.jobs.vacancies.index'));

    $response->assertOk()
        ->assertViewIs('candidate.jobs.vacancies.index')
        ->assertViewHas('jobs');
});

it('displays job detail', function () {
    $job = Job::factory()->create();

    $response = $this->get(route('candidate.jobs.vacancies.show', $job->uuid));

    $response->assertOk()
        ->assertViewIs('candidate.jobs.vacancies.detail')
        ->assertViewHas('job');
});

it('returns 404 for unknown job uuid', function () {
    $response = $this->get(route('candidate.jobs.vacancies.show', '00000000-0000-0000-0000-000000000000'));

    $response->assertNotFound();
});

it('redirects guests to login on apply page', function () {
    $job = Job::factory()->create();

    $response = $this->get(route('candidate.jobs.vacancies.apply', $job->uuid));

    $response->assertRedirect();
});

it('shows apply form for authenticated candidate', function () {
    $candidate = Candidate::factory()->create(['email_verified_at' => now()]);
    $job = Job::factory()->create();

    $this->actingAs($candidate, 'candidate');

    $response = $this->get(route('candidate.jobs.vacancies.apply', $job->uuid));

    $response->assertOk()
        ->assertViewIs('candidate.jobs.vacancies.apply')
        ->assertViewHas('job');
});

it('redirects when candidate already applied', function () {
    $candidate = Candidate::factory()->create(['email_verified_at' => now()]);
    $job = Job::factory()->create();
    $document = Document::factory()->for($candidate)->cv()->create();

    Apply::factory()->forJobAndCandidate($job, $candidate, $document)->create();

    $this->actingAs($candidate, 'candidate');

    $response = $this->get(route('candidate.jobs.vacancies.apply', $job->uuid));

    $response->assertRedirect(route('candidate.jobs.vacancies.show', $job->uuid));
});

it('returns json resource on ajax vacancy search', function () {
    Batch::factory()->active()->create();
    Job::factory()->count(2)->create();

    $response = $this->getJson(route('candidate.jobs.vacancies.index', ['q' => '']), [
        'X-Requested-With' => 'XMLHttpRequest',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['data', 'html', 'meta']);
});
