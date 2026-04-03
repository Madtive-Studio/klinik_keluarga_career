<?php
namespace App\Repositories;

use App\Models\Job;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobRepository
{
    public function __construct(
        private BatchRepository $batchRepo,
        private CategoryRepository $categoryRepo,
        private ApplicationRepository $applicationRepo,
        private CandidateRepository $candidateRepo,
    ) {}

    public function findByUuid($uuid) {
        return Job::with(['category', 'batch', 'applies'])->where('uuid', $uuid)->first();
    }

    public function getByFiltersAndPaginated(array $filters, int $perPage) 
    {
        $searchQuery = $filters['searchQuery'] ?? null;
        $categoryId = $filters['categoryId'] ?? null;
        $jobType = $filters['jobType'] ?? null;
        $batchId = $filters['batchId'] ?? null;

        $query = Job::with(['category', 'batch'])->where('batch_id', $batchId)->orderBy('created_at', 'desc');

        if (!empty($searchQuery)) {
            $keyword = '%' . $searchQuery . '%';
            
            $query->where(function ($q) use ($keyword) {
                $q->whereRaw('LOWER(title) LIKE ?', [$keyword]);
            });
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($jobType)) {
            $query->where('type', $jobType);
        }
        return $query->paginate($perPage);
    }

    /**
     * Business Logic: Get vacancies paginated dengan categories
     */
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

        $jobs = $this->getByFiltersAndPaginated($filters, $perPage);

        return [
            'jobs'       => $jobs,
            'categories' => $this->categoryRepo->getAll(),
        ];
    }

    /**
     * Business Logic: Get vacancy detail untuk display
     */
    public function findVacancyForDisplay(string $uuid): array
    {
        $job = $this->findByUuid($uuid);
        abort_if(!$job, 404);
        $appliesTotal = $job->applies()->count();

        return [
            'job'                  => $job,
            'activeBatch'          => $this->batchRepo->getActiveBatch(),
            'formattedAppliesTotal' => $appliesTotal < 10 ? '0' . $appliesTotal : $appliesTotal,
        ];
    }

    /**
     * Business Logic: Get vacancy apply form data
     */
    public function findVacancyApplyFormData(string $uuid, int $candidateId): array
    {
        $job = $this->findByUuid($uuid);
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