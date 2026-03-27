<?php

namespace Tests\Unit\Services;

use App\Models\Job;
use App\Services\ApplicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApplicationServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function candidateHasAppliedReturnsFalseWhenNoApply(): void
    {
        $job = Job::factory()->create();
        $candidate = \App\Models\Candidate::factory()->create();

        $service = app(ApplicationService::class);

        $this->assertFalse($service->candidateHasApplied($candidate->id, $job));
    }

    #[Test]
    public function candidateHasAppliedReturnsTrueWhenApplyExists(): void
    {
        $candidate = \App\Models\Candidate::factory()->create([
            'email_verified_at' => now(),
        ]);
        $job = Job::factory()->create();
        $document = \App\Models\Document::factory()->for($candidate)->cv()->create();

        \App\Models\Apply::factory()->forJobAndCandidate($job, $candidate, $document)->create();

        $service = app(ApplicationService::class);

        $this->assertTrue($service->candidateHasApplied($candidate->id, $job));
    }
}
