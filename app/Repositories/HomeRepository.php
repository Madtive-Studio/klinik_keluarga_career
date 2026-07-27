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
        $query = Job::with(['category', 'batch'])->latest()->limit($limit);

        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        return $query->get();
    }

    public function getLatestJobsByType(string $jobType, ?int $batchId = null, int $limit = 5): Collection
    {
        $query = Job::with(['category', 'batch'])
            ->where('type', $jobType)
            ->latest()
            ->limit($limit);

        if ($batchId) {
            $query->where('batch_id', $batchId);
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
        $jobsByType = [
            'All' => $this->getLatestJobs(),
        ];

        foreach (array_keys($jobTypes) as $jobType) {
            $jobsByType[$jobType] = $this->getLatestJobsByType($jobType);
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
        $normalizedType = trim((string) $jobType);

        if ($normalizedType === '' || strtoupper($normalizedType) === 'ALL') {
            return $this->getLatestJobs();
        }

        return $this->getLatestJobsByType($normalizedType);
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
