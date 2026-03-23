<?php

namespace App\Services;

use App\Repositories\BatchRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\JobRepository;

class VacancyService
{
    public function __construct(
        private JobRepository $jobRepo,
        private BatchRepository $batchRepo,
        private CategoryRepository $categoryRepo,
    ) {}

    public function getVacancyList(?string $searchQuery, ?string $categoryId, ?string $jobType): array
    {
        $activeBatch = $this->batchRepo->getActiveBatch();
        $activeBatchId = $activeBatch->id ?? 0;

        return [
            'jobs'       => $this->jobRepo->getFilteredJobsPaginated($searchQuery, $categoryId, $jobType, $activeBatchId),
            'categories' => $this->categoryRepo->getAll(),
        ];
    }

    public function getVacancyDetail(string $uuid): array
    {
        $job = $this->jobRepo->findByUuid($uuid);
        $appliesTotal = $job->applies()->count();

        return [
            'job'                  => $job,
            'activeBatch'          => $this->batchRepo->getActiveBatch(),
            'formattedAppliesTotal' => $appliesTotal < 10 ? '0' . $appliesTotal : $appliesTotal,
        ];
    }
}