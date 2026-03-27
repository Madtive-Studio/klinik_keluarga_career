<?php

namespace App\Services;

use App\Repositories\{ApplicationRepository, CategoryRepository, BatchRepository, CandidateRepository, JobRepository};

class VacancyService
{
    public function __construct(
        private ApplicationRepository $applicationRepo,
        private JobRepository $jobRepo,
        private BatchRepository $batchRepo,
        private CategoryRepository $categoryRepo,
        private CandidateRepository $candidateRepo,
    ) {}

    public function getVacanciesPaginated(?string $searchQuery, ?string $categoryId, ?string $jobType, ?int $perPage): array
    {
        $activeBatch = $this->batchRepo->getActiveBatch();
        $activeBatchId = $activeBatch->id ?? 0;

        $filters = [
            'searchQuery' => strtolower($searchQuery),
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

    public function findVacancyForDisplay(string $uuid): array
    {
        $job = $this->jobRepo->findByUuid($uuid);
        abort_if(!$job, 404);
        $appliesTotal = $job->applies()->count();

        return [
            'job'                  => $job,
            'activeBatch'          => $this->batchRepo->getActiveBatch(),
            'formattedAppliesTotal' => $appliesTotal < 10 ? '0' . $appliesTotal : $appliesTotal,
        ];
    }

    public function findVacancyApplyFormData(string $uuid, int $candidateId): array
    {
        $job = $this->jobRepo->findByUuid($uuid);
        abort_if(!$job, 404);
        $appliesTotal = $job->applies()->count();

        $existingApply = $this->applicationRepo->findByJobBatchAndCandidate($job->id, $job->batch->id, $candidateId);
        if ($existingApply) {
            return ['already_applied' => true];
        }

        return [
            'job'                   => $job,
            'activeBatch'           => $this->batchRepo->getActiveBatch(),
            'formattedAppliesTotal' => $appliesTotal < 10 ? '0' . $appliesTotal : $appliesTotal,
            'candidate'             => $this->candidateRepo->findWithDocuments($candidateId),
        ];
    }

}
