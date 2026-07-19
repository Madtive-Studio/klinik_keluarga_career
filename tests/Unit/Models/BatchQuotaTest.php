<?php

namespace Tests\Unit\Models;

use App\Models\Batch;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BatchQuotaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function allocatedAndRemainingQuotaReflectJobQuotas(): void
    {
        $batch = Batch::factory()->create(['quota' => 20]);
        $firstJob = Job::factory()->create(['batch_id' => $batch->id, 'quota' => 7]);
        Job::factory()->create(['batch_id' => $batch->id, 'quota' => 5]);

        $this->assertSame(12, $batch->allocatedQuota());
        $this->assertSame(8, $batch->remainingQuota());
        $this->assertSame(5, $batch->allocatedQuota($firstJob->id));
        $this->assertSame(15, $batch->remainingQuota($firstJob->id));
    }
}
