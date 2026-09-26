<?php

namespace App\Repositories;

use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * HomeRepository - Thick Repository Pattern
 * 
 * Handles both DB operations AND business logic for home page display.
 */
class HomeRepository
{
    public function __construct(
        private BatchRepository $batchRepo,
        private CategoryRepository $categoryRepo,
    ) {}

    // ==================== DB Operations ====================

    public function getLatestJobs(?int $batchId = null, int $limit = 5): Collection
    {
        $query = Job::with(['category', 'batch', 'images'])->latest()->limit($limit);

        if ($batchId) {
            $query->where('batch_id', $batchId);
        } else {
            $query->whereHas('batch', function ($q) {
                $q->where('status', 'ACTIVE')->available();
            });
        }

        return $query->get();
    }

    public function getLatestJobsByType(string $jobType, ?int $batchId = null, int $limit = 5): Collection
    {
        $query = Job::with(['category', 'batch', 'images'])
            ->where('type', $jobType)
            ->latest()
            ->limit($limit);

        if ($batchId) {
            $query->where('batch_id', $batchId);
        } else {
            $query->whereHas('batch', function ($q) {
                $q->where('status', 'ACTIVE')->available();
            });
        }

        return $query->get();
    }

    // ==================== Business Logic ====================

    /**
     * Get semua data untuk home page display
     * Termasuk: batch info, jobs by type, categories, formatted batch label
     */
    public function getHomeDisplayData(array $jobTypes): array
    {
        $activeBatch = $this->batchRepo->getActiveBatch();
        $batchId = $activeBatch?->id;

        $jobsByType = [
            'All' => $activeBatch ? $this->getLatestJobs($batchId) : collect(),
        ];

        foreach (array_keys($jobTypes) as $jobType) {
            $jobsByType[$jobType] = $activeBatch ? $this->getLatestJobsByType($jobType, $batchId) : collect();
        }

        $candidateId = auth('candidate')->id();
        $appliedJobIds = $candidateId
            ? \App\Models\Apply::where('candidate_id', $candidateId)->pluck('job_id')->toArray()
            : [];

        return [
            'activeBatch' => $activeBatch,
            'formattedBatch' => $this->formatBatchLabel($activeBatch),
            'categories' => $this->categoryRepo->getAll(),
            'jobsByType' => $jobsByType,
            'appliedJobIds' => $appliedJobIds,
        ];
    }

    /**
     * Get jobs untuk home page berdasarkan job type
     * Bisa return semua atau filter by type
     */
    public function getJobsByTypeForHome(?string $jobType): Collection
    {
        $activeBatch = $this->batchRepo->getActiveBatch();

        if (!$activeBatch) {
            return collect();
        }

        $normalizedType = trim((string) $jobType);

        if ($normalizedType === '' || strtoupper($normalizedType) === 'ALL') {
            return $this->getLatestJobs($activeBatch->id);
        }

        return $this->getLatestJobsByType($normalizedType, $activeBatch->id);
    }

    /**
     * Helper: Format batch label untuk display
     * Format: CODE - NAME - | START_DATE - END_DATE
     */
    private function formatBatchLabel(object|null $activeBatch): string
    {
        if (!$activeBatch) {
            return __('candidate.home.no_active_batch');
        }

        return sprintf(
            '%s - %s — %s - %s',
            $activeBatch->code,
            $activeBatch->name,
            Carbon::parse($activeBatch->start_date)->translatedFormat('d F Y'),
            Carbon::parse($activeBatch->end_date)->translatedFormat('d F Y'),
        );
    }
}
