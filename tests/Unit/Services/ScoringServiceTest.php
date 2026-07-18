<?php

namespace Tests\Unit\Services;

use App\Enums\ScoreRecommendation;
use App\Models\Candidate;
use App\Models\CandidateProfile;
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

        $job = Job::factory()->create(['experience' => '2 Tahun']);
        JobCriteria::factory()->for($job)->create([
            'min_education' => 'S1',
        ]);

        $result = $this->service->calculate($candidate, $job, str_repeat('Motivasi melamar posisi ini. ', 5));

        $this->assertGreaterThanOrEqual(70, $result['score']);
        $this->assertSame(ScoreRecommendation::SHORTLIST->value, $result['recommendation']);
        $this->assertArrayNotHasKey('skills', $result['breakdown']);
    }

    #[Test]
    public function itReturnsRejectRecommendationForWeakCandidate(): void
    {
        $candidate = Candidate::factory()->create();
        CandidateProfile::factory()->for($candidate)->create([
            'education_level' => 'SMA',
            'years_of_experience' => 0,
        ]);

        $job = Job::factory()->create(['experience' => '3 tahun']);
        JobCriteria::factory()->for($job)->create([
            'min_education' => 'S1',
        ]);

        $result = $this->service->calculate($candidate, $job, 'Singkat');

        $this->assertLessThan(40, $result['score']);
        $this->assertSame(ScoreRecommendation::REJECT->value, $result['recommendation']);
        $this->assertSame(0, $result['breakdown']['education']);
    }

    #[Test]
    public function itAcceptsD4CandidateForD3MinimumEducation(): void
    {
        $candidate = Candidate::factory()->create();
        CandidateProfile::factory()->for($candidate)->create([
            'education_level' => 'D4',
            'major' => 'Keperawatan',
            'years_of_experience' => 2,
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
        ]);

        $job = Job::factory()->create(['experience' => 'Fresh Graduate']);
        JobCriteria::factory()->for($job)->create([
            'min_education' => 'D3',
            'weight_education' => 30,
            'weight_experience' => 30,
            'weight_profile' => 20,
            'weight_cover_letter' => 20,
        ]);

        $result = $this->service->calculate($candidate, $job, str_repeat('Saya sangat tertarik dengan posisi ini. ', 4));

        $this->assertSame(30, $result['breakdown']['education']);
    }

    #[Test]
    public function itUsesJobExperienceFieldWhenScoringExperience(): void
    {
        $candidate = Candidate::factory()->create();
        CandidateProfile::factory()->for($candidate)->create([
            'education_level' => 'S1',
            'years_of_experience' => 1,
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
        ]);

        $job = Job::factory()->create(['experience' => '2 Tahun']);
        JobCriteria::factory()->for($job)->create([
            'min_education' => null,
            'weight_education' => 0,
            'weight_experience' => 20,
            'weight_profile' => 0,
            'weight_cover_letter' => 0,
        ]);

        $result = $this->service->calculate($candidate, $job, '');

        $this->assertSame(10, $result['breakdown']['experience']);
    }
}
