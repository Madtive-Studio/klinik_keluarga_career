<?php

namespace App\Repositories;

use App\Models\Apply;
use App\Models\CV;

class ApplicationRepository
{
    public function findByJobBatchAndCandidate(int $jobId, int $batchId, int $candidateId): ?Apply
    {
        return Apply::where('job_id', $jobId)
                    ->where('batch_id', $batchId)
                    ->where('candidate_id', $candidateId)
                    ->first();
    }

    public function findWithJobAndCandidate(string $jobUuid, int $candidateId): ?Apply
    {
        return Apply::with(['job', 'candidate', 'job.category', 'batch'])
                    ->whereHas('job', function ($q) use ($jobUuid) {
                        $q->where('uuid', $jobUuid);
                    })
                    ->where('candidate_id', $candidateId)
                    ->first();
    }

    public function create(array $data): Apply
    {
        return Apply::create($data);
    }

    public function findByCandidateWithFilters(int $candidateId, ?string $status, int $year)
    {
        return Apply::with(['job.category'])
                    ->where('candidate_id', $candidateId)
                    ->when($status, fn($q) => $q->where('status', $status))
                    ->whereYear('created_at', $year)
                    ->get();
    }
}