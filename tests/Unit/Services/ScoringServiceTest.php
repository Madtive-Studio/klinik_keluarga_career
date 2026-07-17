<?php

namespace Tests\Unit\Services;

use App\Enums\ScoreRecommendation;
use App\Models\Candidate;
use App\Models\CandidateProfile;
use App\Models\CandidateSkill;
use App\Models\Job;
use App\Models\JobCriteria;
use App\Services\ScoringService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ScoringServiceTest extends TestCase
{
    use RefreshDatabase;

    private ScoringService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ScoringService::class);
    }

    #[Test]
    public function itReturnsShortlistRecommendationForStrongCandidate(): void
    {
        $candidate = Candidate::factory()->create();
        CandidateProfile::factory()->for($candidate)->create([
            'education_level' => 'S1',
            'major' => 'Keperawatan',
            'years_of_experience' => 3,
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
        ]);
        CandidateSkill::create(['candidate_id' => $candidate->id, 'name' => 'Komunikasi', 'level' => 'advanced']);
        CandidateSkill::create(['candidate_id' => $candidate->id, 'name' => 'Microsoft Office', 'level' => 'intermediate']);

        $job = Job::factory()->create();
        JobCriteria::factory()->for($job)->create([
            'min_education' => 'S1',
            'min_experience_years' => 2,
            'required_skills' => ['Komunikasi', 'Microsoft Office'],
        ]);

        $result = $this->service->calculate($candidate, $job, str_repeat('Motivasi melamar posisi ini. ', 5));

        $this->assertGreaterThanOrEqual(70, $result['score']);
        $this->assertSame(ScoreRecommendation::SHORTLIST->value, $result['recommendation']);
    }

    #[Test]
    public function itReturnsRejectRecommendationForWeakCandidate(): void
    {
        $candidate = Candidate::factory()->create();
        CandidateProfile::factory()->for($candidate)->create([
            'education_level' => 'SMA',
            'years_of_experience' => 0,
        ]);

        $job = Job::factory()->create();
        JobCriteria::factory()->for($job)->create([
            'min_education' => 'S1',
            'min_experience_years' => 3,
            'required_skills' => ['Keperawatan', 'ICU'],
        ]);

        $result = $this->service->calculate($candidate, $job, 'Singkat');

        $this->assertLessThan(40, $result['score']);
        $this->assertSame(ScoreRecommendation::REJECT->value, $result['recommendation']);
    }
}
