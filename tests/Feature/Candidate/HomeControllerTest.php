<?php

use App\Models\Batch;
use App\Models\Job;

it('displays home page with expected data', function () {
    $batch = Batch::factory()->active()->create();
    Job::factory()->count(3)->create(['batch_id' => $batch->id]);

    $response = $this->get(route('candidate.home'));

    $response->assertOk()
        ->assertViewIs('candidate.home')
        ->assertViewHas('jobsByType')
        ->assertViewHas('jobTypes')
        ->assertViewHas('categories')
        ->assertViewHas('formattedBatch');
});

it('returns job list html via ajax', function () {
    $batch = Batch::factory()->active()->create();
    Job::factory()->count(2)->internship()->create(['batch_id' => $batch->id]);

    $response = $this->getJson(route('candidate.home.jobs-by-type', [
        'job_type' => 'Internship',
    ]));

    $response->assertOk()
        ->assertJsonStructure(['data', 'html']);
});

it('shows message when no active batch exists', function () {
    Batch::factory()->inactive()->create();

    $response = $this->get(route('candidate.home'));

    $response->assertOk()
        ->assertViewHas('message', 'Belum ada batch yang aktif');
});
