<?php

namespace Tests\Unit\Repositories;

use App\Models\Batch;
use App\Repositories\BatchRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BatchRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private BatchRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new BatchRepository();
    }

    #[Test]
    public function getActiveBatchReturnsFirstActiveBatch(): void
    {
        Batch::factory()->inactive()->create();
        $active = Batch::factory()->active()->create(['code' => 'BATCH-ACTIVE']);

        $result = $this->repository->getActiveBatch();

        $this->assertNotNull($result);
        $this->assertSame($active->id, $result->id);
        $this->assertSame('ACTIVE', $result->status);
    }

    #[Test]
    public function getActiveBatchReturnsNullWhenNoActiveBatch(): void
    {
        Batch::factory()->inactive()->create();

        $this->assertNull($this->repository->getActiveBatch());
    }
}
