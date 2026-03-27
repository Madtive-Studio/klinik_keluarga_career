<?php

namespace Tests\Unit\Repositories;

use App\Enums\JobType;
use App\Models\Job;
use App\Repositories\HomeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private HomeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new HomeRepository();
    }

    #[Test]
    public function getLatestJobsByBatchReturnsEmptyCollectionWhenBatchIsNull(): void
    {
        $result = $this->repository->getLatestJobsByBatch(null);

        $this->assertTrue($result->isEmpty());
    }

    #[Test]
    public function getLatestJobsByBatchReturnsLatestJobsWithLimit(): void
    {
        $job = Job::factory()->create();
        Job::factory()->count(6)->create(['batch_id' => $job->batch_id]);

        $result = $this->repository->getLatestJobsByBatch($job->batch_id, 5);

        $this->assertCount(5, $result);
        $this->assertTrue($result->first()->created_at->gte($result->last()->created_at));
    }

    #[Test]
    public function getLatestJobsByBatchAndTypeFiltersByType(): void
    {
        $job = Job::factory()->create();
        Job::factory()->wfhRemote()->create(['batch_id' => $job->batch_id]);
        Job::factory()->internship()->create(['batch_id' => $job->batch_id]);

        $result = $this->repository->getLatestJobsByBatchAndType($job->batch_id, JobType::WFH_REMOTE->value, 10);

        $this->assertGreaterThanOrEqual(1, $result->count());
        $this->assertTrue($result->every(fn ($item) => $item->type === JobType::WFH_REMOTE->value));
    }
}
