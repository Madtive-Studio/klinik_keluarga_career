<?php

namespace Tests\Unit\Repositories;

use App\Enums\JobType;
use App\Models\Category;
use App\Models\Job;
use App\Repositories\JobRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JobRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private JobRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new JobRepository();
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
}
