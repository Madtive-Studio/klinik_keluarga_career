<?php

namespace Tests\Unit\Repositories;

use App\Enums\JobType;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Job;
use App\Repositories\HomeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Unit Test for HomeRepository (Thick Repository Pattern).
 * 
 * This repository contains both DB operations AND business logic for home page.
 * Tests cover both layers: data fetching and business logic orchestration.
 */
class HomeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private HomeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        // Use service container to auto-resolve dependencies
        $this->repository = $this->app->make(HomeRepository::class);
    }

    // =========================================================
    // DB Operations Tests
    // =========================================================

    #[Test]
    public function getLatestJobsReturnsAllJobsWhenBatchIsNull(): void
    {
        Job::factory()->count(3)->create();

        $result = $this->repository->getLatestJobs();

        $this->assertCount(3, $result);
    }

    #[Test]
    public function getLatestJobsFiltersByBatchWhenProvided(): void
    {
        $job = Job::factory()->create();
        Job::factory()->count(3)->create(); // other batch

        $result = $this->repository->getLatestJobs($job->batch_id, 10);

        $this->assertCount(1, $result);
    }

    #[Test]
    public function getLatestJobsRespectsLimit(): void
    {
        $batch = Batch::factory()->create();
        Job::factory()->count(6)->create(['batch_id' => $batch->id]);

        $result = $this->repository->getLatestJobs($batch->id, 5);

        $this->assertCount(5, $result);
        $this->assertTrue($result->first()->created_at->gte($result->last()->created_at));
    }

    #[Test]
    public function getLatestJobsByTypeFiltersByType(): void
    {
        $batch = Batch::factory()->create();
        Job::factory()->count(2)->wfhRemote()->create(['batch_id' => $batch->id]);
        Job::factory()->internship()->create(['batch_id' => $batch->id]);

        $result = $this->repository->getLatestJobsByType(JobType::WFH_REMOTE->value, $batch->id, 10);

        $this->assertCount(2, $result);
        $this->assertTrue($result->every(fn ($item) => $item->type === JobType::WFH_REMOTE->value));
    }

    // =========================================================
    // Business Logic Tests
    // =========================================================

    #[Test]
    public function getHomeDisplayDataReturnsAllRequiredKeys(): void
    {
        $batch = Batch::factory()->create();
        $batch->update(['status' => 'ACTIVE']);
        Category::factory()->count(3)->create();

        $jobTypes = [
            JobType::WFH_REMOTE->value => JobType::WFH_REMOTE,
            JobType::FULLTIME_ONSITE->value => JobType::FULLTIME_ONSITE,
        ];

        $data = $this->repository->getHomeDisplayData($jobTypes);

        $this->assertArrayHasKey('activeBatch', $data);
        $this->assertArrayHasKey('formattedBatch', $data);
        $this->assertArrayHasKey('categories', $data);
        $this->assertArrayHasKey('jobsByType', $data);
    }

    #[Test]
    public function getHomeDisplayDataIncludesAllJobTypesInJobsByType(): void
    {
        $batch = Batch::factory()->create();
        $batch->update(['status' => 'ACTIVE']);
        $jobTypes = [
            JobType::WFH_REMOTE->value => JobType::WFH_REMOTE,
            JobType::INTERNSHIP->value => JobType::INTERNSHIP,
        ];

        $data = $this->repository->getHomeDisplayData($jobTypes);
        $jobsByType = $data['jobsByType'];

        $this->assertArrayHasKey('All', $jobsByType);
        $this->assertArrayHasKey(JobType::WFH_REMOTE->value, $jobsByType);
        $this->assertArrayHasKey(JobType::INTERNSHIP->value, $jobsByType);
    }

    #[Test]
    public function getJobsByTypeForHomeReturnsAllJobsWhenTypeIsNull(): void
    {
        $batch = Batch::factory()->create();
        $batch->update(['status' => 'ACTIVE']);
        Job::factory()->count(3)->create(['batch_id' => $batch->id]);

        $result = $this->repository->getJobsByTypeForHome(null);

        $this->assertCount(3, $result);
    }

    #[Test]
    public function getJobsByTypeForHomeReturnsAllJobsWhenTypeIsAllKeyword(): void
    {
        $batch = Batch::factory()->create();
        $batch->update(['status' => 'ACTIVE']);
        Job::factory()->count(3)->create(['batch_id' => $batch->id]);

        $result = $this->repository->getJobsByTypeForHome('All');

        $this->assertCount(3, $result);
    }

    #[Test]
    public function getJobsByTypeForHomeFiltersJobsBySpecificType(): void
    {
        $batch = Batch::factory()->create();
        $batch->update(['status' => 'ACTIVE']);
        Job::factory()->fulltime()->create(['batch_id' => $batch->id]);
        Job::factory()->wfhRemote()->create(['batch_id' => $batch->id]);
        Job::factory()->count(2)->internship()->create(['batch_id' => $batch->id]);

        $result = $this->repository->getJobsByTypeForHome(JobType::INTERNSHIP->value);

        $this->assertGreaterThanOrEqual(2, $result->count());
        $this->assertTrue($result->every(fn ($item) => $item->type === JobType::INTERNSHIP->value));
    }

    #[Test]
    public function getJobsByTypeForHomeHandlesEmptyStringAsAll(): void
    {
        $batch = Batch::factory()->create();
        $batch->update(['status' => 'ACTIVE']);
        Job::factory()->count(2)->create(['batch_id' => $batch->id]);

        $result = $this->repository->getJobsByTypeForHome('   ');

        $this->assertCount(2, $result);
    }
}
