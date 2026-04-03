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

    // ==================== Business Logic ====================

    /**
     * Get semua data untuk home page display
     * Termasuk: batch info, jobs by type, categories, formatted batch label
     */
    public function getHomeDisplayData(array $jobTypes): array
    {
        $activeBatch = $this->batchRepo->getActiveBatch();
        $activeBatchId = $activeBatch?->id;
        $jobsByType = [
            'All' => $this->getLatestJobsByBatch($activeBatchId),
        ];

        foreach (array_keys($jobTypes) as $jobType) {
            $jobsByType[$jobType] = $this->getLatestJobsByBatchAndType($activeBatchId, $jobType);
        }

        return [
            'activeBatch' => $activeBatch,
            'formattedBatch' => $this->formatBatchLabel($activeBatch),
            'categories' => $this->categoryRepo->getAll(),
            'jobsByType' => $jobsByType,
        ];
    }

    /**
     * Get jobs untuk home page berdasarkan job type
     * Bisa return semua atau filter by type
     */
    public function getJobsByTypeForHome(?string $jobType): Collection
    {
        $activeBatchId = $this->batchRepo->getActiveBatch()?->id;
        $normalizedType = trim((string) $jobType);

        if ($normalizedType === '' || strtoupper($normalizedType) === 'ALL') {
            return $this->getLatestJobsByBatch($activeBatchId);
        }

        return $this->getLatestJobsByBatchAndType($activeBatchId, $normalizedType);
    }

    /**
     * Helper: Format batch label untuk display
     * Format: CODE - NAME - | START_DATE - END_DATE
     */
    private function formatBatchLabel(object|null $activeBatch): string
    {
        if (!$activeBatch) {
            return 'No active batch available';
        }

        return sprintf(
            '%s - %s - | %s - %s',
            $activeBatch->code,
            $activeBatch->name,
            Carbon::parse($activeBatch->start_date)->translatedFormat('d F Y'),
            Carbon::parse($activeBatch->end_date)->translatedFormat('d F Y'),
        );
    }
}
