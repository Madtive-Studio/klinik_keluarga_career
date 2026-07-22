<?php

namespace Tests\Unit\Repositories;

use App\Enums\EducationLevel;
use App\Enums\JobType;
use App\Models\Apply;
use App\Models\Batch;
use App\Models\Candidate;
use App\Models\CandidateProfile;
use App\Models\Category;
use App\Models\Document;
use App\Models\Job;
use App\Models\JobCriteria;
use App\Repositories\JobRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JobRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private JobRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(JobRepository::class);
    }

    #[Test]
    public function findByUuidReturnsNullWhenNotFound(): void
    {
        $this->assertNull($this->repository->findByUuid('00000000-0000-0000-0000-000000000000'));
    }

    #[Test]
    public function findByUuidReturnsJobWithRelationsLoaded(): void
    {
        $job = Job::factory()->create();

        $result = $this->repository->findByUuid($job->uuid);

        $this->assertNotNull($result);
        $this->assertSame($job->id, $result->id);
        $this->assertTrue($result->relationLoaded('category'));
        $this->assertTrue($result->relationLoaded('batch'));
        $this->assertTrue($result->relationLoaded('applies'));
    }

    #[Test]
    public function getByFiltersAndPaginatedScopesToBatchAndReturnsPaginator(): void
    {
        $jobA = Job::factory()->create();
        $batchId = $jobA->batch_id;
        Job::factory()->create(); // other batch

        $filters = [
            'searchQuery' => null,
            'categoryId' => null,
            'jobType' => null,
            'batchId' => $batchId,
        ];

        $paginator = $this->repository->getByFiltersAndPaginated($filters, 10);

        $this->assertSame(1, $paginator->total());
        $this->assertSame($jobA->id, $paginator->first()->id);
    }

    #[Test]
    public function getByFiltersAndPaginatedFiltersBySearchCategoryAndJobType(): void
    {
        $batchId = Job::factory()->create()->batch_id;

        $match = Job::factory()->create([
            'batch_id' => $batchId,
            'title' => 'Backend Developer Senior',
            'category_id' => $cat = Category::factory()->create()->id,
            'type' => JobType::FULLTIME_ONSITE->value,
        ]);
        Job::factory()->create([
            'batch_id' => $batchId,
            'title' => 'Designer',
            'category_id' => $cat,
            'type' => JobType::INTERNSHIP->value,
        ]);

        $filters = [
            'searchQuery' => 'backend',
            'categoryId' => (string) $cat,
            'jobType' => JobType::FULLTIME_ONSITE->value,
            'batchId' => $batchId,
        ];

        $paginator = $this->repository->getByFiltersAndPaginated($filters, 10);

        $this->assertSame(1, $paginator->total());
        $this->assertSame($match->id, $paginator->first()->id);
    }

    #[Test]
    public function getVacanciesPaginatedReturnsJobsAndCategories(): void
    {
        $batch = Batch::factory()->active()->create();
        $category = Category::factory()->create();
        Job::factory()->create([
            'batch_id' => $batch->id,
            'category_id' => $category->id,
        ]);

        $result = $this->repository->getVacanciesPaginated(null, null, null, 10);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('jobs', $result);
        $this->assertArrayHasKey('categories', $result);
        $this->assertSame(1, $result['jobs']->total());
    }

    #[Test]
    public function findVacancyForDisplayReturnsJobWithAppliesCount(): void
    {
        $job = Job::factory()->create();
        $candidate = Candidate::factory()->create(['email_verified_at' => now()]);
        $document = Document::factory()->for($candidate)->cv()->create();
        Apply::factory()->forJobAndCandidate($job, $candidate, $document)->create();

        $result = $this->repository->findVacancyForDisplay($job->uuid);

        $this->assertArrayHasKey('job', $result);
        $this->assertArrayHasKey('activeBatch', $result);
        $this->assertArrayHasKey('formattedAppliesTotal', $result);
        $this->assertArrayHasKey('applyEligibility', $result);
        $this->assertSame('01', $result['formattedAppliesTotal']);
    }

    #[Test]
    public function findVacancyApplyFormDataReturnsFormDataWhenNotApplied(): void
    {
        $job = Job::factory()->create();
        $candidate = Candidate::factory()->create(['email_verified_at' => now()]);
        CandidateProfile::factory()->for($candidate)->create();

        $result = $this->repository->findVacancyApplyFormData($job->uuid, $candidate->id);

        $this->assertArrayHasKey('job', $result);
        $this->assertArrayHasKey('candidate', $result);
        $this->assertArrayNotHasKey('already_applied', $result);
    }

    #[Test]
    public function findVacancyApplyFormDataReturnsAlreadyAppliedWhenExists(): void
    {
        $job = Job::factory()->create();
        $candidate = Candidate::factory()->create(['email_verified_at' => now()]);
        $document = Document::factory()->for($candidate)->cv()->create();
        Apply::factory()->forJobAndCandidate($job, $candidate, $document)->create();

        $result = $this->repository->findVacancyApplyFormData($job->uuid, $candidate->id);

        $this->assertArrayHasKey('already_applied', $result);
        $this->assertTrue($result['already_applied']);
    }

    #[Test]
    public function getByFiltersAndPaginatedFiltersBySalaryMin(): void
    {
        $batch = Batch::factory()->create();
        $batchId = $batch->id;

        $match = Job::factory()->create([
            'batch_id' => $batchId,
            'salary_max' => 5_000_000,
        ]);
        Job::factory()->create([
            'batch_id' => $batchId,
            'salary_max' => 2_000_000,
        ]);

        $filters = [
            'searchQuery' => null,
            'categoryId' => null,
            'jobType' => null,
            'batchId' => $batchId,
            'salaryMin' => '3000000',
            'salaryMax' => null,
            'minEducation' => null,
        ];

        $paginator = $this->repository->getByFiltersAndPaginated($filters, 10);

        $this->assertSame(1, $paginator->total());
        $this->assertSame($match->id, $paginator->first()->id);
    }

    #[Test]
    public function getByFiltersAndPaginatedFiltersBySalaryMax(): void
    {
        $batch = Batch::factory()->create();
        $batchId = $batch->id;

        $match = Job::factory()->create([
            'batch_id' => $batchId,
            'salary_min' => 2_000_000,
        ]);
        Job::factory()->create([
            'batch_id' => $batchId,
            'salary_min' => 8_000_000,
        ]);

        $filters = [
            'searchQuery' => null,
            'categoryId' => null,
            'jobType' => null,
            'batchId' => $batchId,
            'salaryMin' => null,
            'salaryMax' => '5000000',
            'minEducation' => null,
        ];

        $paginator = $this->repository->getByFiltersAndPaginated($filters, 10);

        $this->assertSame(1, $paginator->total());
        $this->assertSame($match->id, $paginator->first()->id);
    }

    #[Test]
    public function getByFiltersAndPaginatedFiltersByMinEducation(): void
    {
        $batch = Batch::factory()->create();
        $batchId = $batch->id;

        $match = Job::factory()->create(['batch_id' => $batchId]);
        JobCriteria::factory()->for($match)->create(['min_education' => 'S3']);

        $mismatch = Job::factory()->create(['batch_id' => $batchId]);
        JobCriteria::factory()->for($mismatch)->create(['min_education' => 'SMA']);

        $filters = [
            'searchQuery' => null,
            'categoryId' => null,
            'jobType' => null,
            'batchId' => $batchId,
            'salaryMin' => null,
            'salaryMax' => null,
            'minEducation' => 'D3',
        ];

        $paginator = $this->repository->getByFiltersAndPaginated($filters, 10);

        $this->assertSame(1, $paginator->total());
        $this->assertSame($match->id, $paginator->first()->id);
    }

    #[Test]
    public function getVacanciesPaginatedAcceptsSalaryAndEducationFilters(): void
    {
        $batch = Batch::factory()->active()->create();
        $category = Category::factory()->create();

        $high = Job::factory()->create([
            'batch_id' => $batch->id,
            'category_id' => $category->id,
            'salary_max' => 10_000_000,
        ]);
        JobCriteria::factory()->for($high)->create(['min_education' => 'S1']);

        $low = Job::factory()->create([
            'batch_id' => $batch->id,
            'category_id' => $category->id,
            'salary_max' => 2_000_000,
        ]);
        JobCriteria::factory()->for($low)->create(['min_education' => 'SMA']);

        $result = $this->repository->getVacanciesPaginated(
            searchQuery: null,
            categoryId: null,
            jobType: null,
            perPage: 10,
            salaryMin: '5000000',
            salaryMax: null,
            minEducation: 'SMA',
        );

        $this->assertSame(1, $result['jobs']->total());
        $this->assertSame($high->id, $result['jobs']->first()->id);
    }
}
