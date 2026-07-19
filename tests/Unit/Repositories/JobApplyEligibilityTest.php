<?php

namespace Tests\Unit\Repositories;

use App\Enums\EducationLevel;
use App\Models\Apply;
use App\Models\Candidate;
use App\Models\CandidateProfile;
use App\Models\Document;
use App\Models\Job;
use App\Models\JobCriteria;
use App\Repositories\JobRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JobApplyEligibilityTest extends TestCase
{
    use RefreshDatabase;

    private JobRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(JobRepository::class);
    }

    #[Test]
    public function getApplyEligibilityBlocksDuplicateApplications(): void
    {
        $job = Job::factory()->create();
        $candidate = Candidate::factory()->create(['email_verified_at' => now()]);
        CandidateProfile::factory()->for($candidate)->create([
            'education_level' => EducationLevel::S1->value,
        ]);
        $document = Document::factory()->for($candidate)->cv()->create();
        Apply::factory()->forJobAndCandidate($job, $candidate, $document)->create();

        $eligibility = $this->repository->getApplyEligibility($job, $candidate->id);

        $this->assertFalse($eligibility['can_apply']);
        $this->assertTrue($eligibility['already_applied']);
    }

    #[Test]
    public function getApplyEligibilityBlocksWhenEducationBelowRequirement(): void
    {
        $job = Job::factory()->create();
        JobCriteria::factory()->for($job)->create([
            'min_education' => EducationLevel::S2->value,
        ]);
        $candidate = Candidate::factory()->create(['email_verified_at' => now()]);
        CandidateProfile::factory()->for($candidate)->create([
            'education_level' => EducationLevel::S1->value,
        ]);

        $eligibility = $this->repository->getApplyEligibility($job->fresh(), $candidate->id);

        $this->assertFalse($eligibility['can_apply']);
        $this->assertTrue($eligibility['education_not_met']);
    }

    #[Test]
    public function getApplyEligibilityAllowsWhenEducationMeetsRequirement(): void
    {
        $job = Job::factory()->create();
        JobCriteria::factory()->for($job)->create([
            'min_education' => EducationLevel::S1->value,
        ]);
        $candidate = Candidate::factory()->create(['email_verified_at' => now()]);
        CandidateProfile::factory()->for($candidate)->create([
            'education_level' => EducationLevel::S2->value,
        ]);

        $eligibility = $this->repository->getApplyEligibility($job->fresh(), $candidate->id);

        $this->assertTrue($eligibility['can_apply']);
        $this->assertFalse($eligibility['education_not_met']);
    }
}
