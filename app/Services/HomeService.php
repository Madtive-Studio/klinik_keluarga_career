<?php

namespace App\Services;

use App\Repositories\BatchRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\HomeRepository;
use Carbon\Carbon;

class HomeService
{
    public function __construct(
        private HomeRepository $homeRepo,
        private BatchRepository $batchRepo,
        private CategoryRepository $categoryRepo,
    ) {}

    public function getHomeDisplayData(array $jobTypes): array
    {
        $activeBatch = $this->batchRepo->getActiveBatch();
        $activeBatchId = $activeBatch?->id;
        $jobsByType = [
            'All' => $this->homeRepo->getLatestJobsByBatch($activeBatchId),
        ];

        foreach (array_keys($jobTypes) as $jobType) {
            $jobsByType[$jobType] = $this->homeRepo->getLatestJobsByBatchAndType($activeBatchId, $jobType);
        }

        return [
            'activeBatch' => $activeBatch,
            'formattedBatch' => $this->formatBatchLabel($activeBatch),
            'categories' => $this->categoryRepo->getAll(),
            'jobsByType' => $jobsByType,
        ];
    }

    public function getJobsByTypeForHome(?string $jobType): \Illuminate\Support\Collection
    {
        $activeBatchId = $this->batchRepo->getActiveBatch()?->id;
        $normalizedType = trim((string) $jobType);

        if ($normalizedType === '' || strtoupper($normalizedType) === 'ALL') {
            return $this->homeRepo->getLatestJobsByBatch($activeBatchId);
        }

        return $this->homeRepo->getLatestJobsByBatchAndType($activeBatchId, $normalizedType);
    }

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
