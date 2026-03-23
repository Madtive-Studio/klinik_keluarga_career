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

    public function getVacancyListPaginated(?string $searchQuery, ?string $categoryId, ?string $jobType, ?int $perPage): array
    {
        $activeBatch = $this->batchRepo->getActiveBatch();
        $activeBatchId = $activeBatch->id ?? 0;

        $filters = [
            'searchQuery' => $searchQuery,
            'categoryId' => $categoryId,
            'jobType' => $jobType,
            'batchId' => $activeBatchId,
        ];

        $jobs = $this->jobRepo->getByFiltersAndPaginated($filters, $perPage);

        return [
            'jobs'       => $jobs,
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