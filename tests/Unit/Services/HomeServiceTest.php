<?php

namespace Tests\Unit\Services;

use App\Enums\JobType;
use App\Repositories\BatchRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\HomeRepository;
use App\Services\HomeService;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomeServiceTest extends TestCase
{
    private HomeRepository|MockInterface $homeRepo;
    private BatchRepository|MockInterface $batchRepo;
    private CategoryRepository|MockInterface $categoryRepo;
    private HomeService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->homeRepo = Mockery::mock(HomeRepository::class);
        $this->batchRepo = Mockery::mock(BatchRepository::class);
        $this->categoryRepo = Mockery::mock(CategoryRepository::class);

        $this->service = new HomeService(
            $this->homeRepo,
            $this->batchRepo,
            $this->categoryRepo,
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function getHomeDisplayDataReturnsEmptyJobCollectionsWhenNoActiveBatch(): void
    {
        $jobTypes = JobType::getWithLabels();

        $this->batchRepo->shouldReceive('getActiveBatch')->once()->andReturn(null);
        $this->categoryRepo->shouldReceive('getAll')->once()->andReturn(collect());
        $this->homeRepo->shouldReceive('getLatestJobsByBatch')->once()->with(null)->andReturn(collect());
        $this->homeRepo->shouldReceive('getLatestJobsByBatchAndType')->times(count($jobTypes))->andReturn(collect());

        $result = $this->service->getHomeDisplayData($jobTypes);

        $this->assertSame('No active batch available', $result['formattedBatch']);
        $this->assertTrue($result['jobsByType']['All']->isEmpty());
        $this->assertTrue($result['jobsByType'][JobType::WFH_REMOTE->value]->isEmpty());
    }

    #[Test]
    public function getHomeDisplayDataBuildsBatchLabelAndJobsByType(): void
    {
        $jobTypes = JobType::getWithLabels();
        $batch = (object) [
            'id' => 10,
            'code' => 'BATCH-2026-01',
            'name' => 'Batch 1',
            'start_date' => '2026-01-01 00:00:00',
            'end_date' => '2026-06-30 23:59:59',
        ];

        $this->batchRepo->shouldReceive('getActiveBatch')->once()->andReturn($batch);
        $this->categoryRepo->shouldReceive('getAll')->once()->andReturn(collect([(object) ['name' => 'IT']]));
        $this->homeRepo->shouldReceive('getLatestJobsByBatch')->once()->with(10)->andReturn(collect([1]));
        $this->homeRepo->shouldReceive('getLatestJobsByBatchAndType')->times(count($jobTypes))->andReturn(collect([1]));

        $result = $this->service->getHomeDisplayData($jobTypes);

        $this->assertStringContainsString('BATCH-2026-01', $result['formattedBatch']);
        $this->assertSame(1, $result['jobsByType']['All']->count());
        $this->assertSame(1, $result['categories']->count());
    }

    #[Test]
    public function getJobsByTypeForHomeReturnsAllJobsWhenTypeIsAll(): void
    {
        $batch = (object) ['id' => 10];
        $this->batchRepo->shouldReceive('getActiveBatch')->once()->andReturn($batch);
        $this->homeRepo->shouldReceive('getLatestJobsByBatch')->once()->with(10)->andReturn(collect([1, 2]));

        $result = $this->service->getJobsByTypeForHome('All');

        $this->assertCount(2, $result);
    }

    #[Test]
    public function getJobsByTypeForHomeReturnsTypeSpecificJobsWhenTypeProvided(): void
    {
        $batch = (object) ['id' => 10];
        $this->batchRepo->shouldReceive('getActiveBatch')->once()->andReturn($batch);
        $this->homeRepo->shouldReceive('getLatestJobsByBatchAndType')
            ->once()
            ->with(10, JobType::INTERNSHIP->value)
            ->andReturn(collect([1]));

        $result = $this->service->getJobsByTypeForHome(JobType::INTERNSHIP->value);

        $this->assertCount(1, $result);
    }
}
