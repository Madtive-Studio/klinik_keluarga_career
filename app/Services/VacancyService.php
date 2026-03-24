<?php

namespace App\Services;

use App\Models\Job;
use App\Repositories\{ApplicationRepository, CategoryRepository, BatchRepository, CandidateRepository, JobRepository};

class VacancyService
{
    public function __construct(
        private ApplicationService $applicationService,

        private ApplicationRepository $applicationRepo,
        private JobRepository $jobRepo,
        private BatchRepository $batchRepo,
        private CategoryRepository $categoryRepo,
        private CandidateRepository $candidateRepo,
    ) {}

    public function getVacancyListPaginated(?string $searchQuery, ?string $categoryId, ?string $jobType, ?int $perPage): array
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

    public function getVacanyAppliesFormData(string $uuid, int $candidateId): array
    {
        $job = $this->jobRepo->findByUuid($uuid);
        $appliesTotal = $job->applies()->count();

        $hasApplied = $this->applicationService->IsCandidateHasApplied($candidateId, $job);
        if ($hasApplied && $hasApplied ) {
            return ['already_applied' => true];
        }

        return [
            'job'                   => $job,
            'activeBatch'           => $this->batchRepo->getActiveBatch(),
            'formattedAppliesTotal' => $appliesTotal < 10 ? '0' . $appliesTotal : $appliesTotal,
            'hasApplied'            => $this->applicationRepo->findByJobBatchAndCandidate($job->id, $job->batch->id, $candidateId),
            'candidate'             => $this->candidateRepo->findWithDocuments($candidateId),
        ];
    }

}