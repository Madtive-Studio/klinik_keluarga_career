<?php

namespace App\Repositories;

use App\Models\Job;
use Illuminate\Support\Collection;

class HomeRepository
{
    public function getLatestJobsByBatch(?int $batchId, int $limit = 5): Collection
    {
        if (!$batchId) {
            return collect();
        }

        return Job::with(['category', 'batch'])
            ->where('batch_id', $batchId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getLatestJobsByBatchAndType(?int $batchId, string $jobType, int $limit = 5): Collection
    {
        if (!$batchId) {
            return collect();
        }

        return Job::with(['category', 'batch'])
            ->where('batch_id', $batchId)
            ->where('type', $jobType)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
