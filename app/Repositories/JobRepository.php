<?php
namespace App\Repositories;

use App\Enums\EducationLevel;
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
        return Job::with(['category', 'batch', 'applies', 'images'])->where('uuid', $uuid)->first();
    }

    public function getByFiltersAndPaginated(array $filters, int $perPage) 
    {
        $searchQuery = $filters['searchQuery'] ?? null;
        $categoryId = $filters['categoryId'] ?? null;
        $jobType = $filters['jobType'] ?? null;
        $batchId = $filters['batchId'] ?? null;
        $salaryMin = $filters['salaryMin'] ?? null;
        $salaryMax = $filters['salaryMax'] ?? null;
        $minEducation = $filters['minEducation'] ?? null;

        $query = Job::with(['category', 'batch', 'criteria', 'images'])
            ->orderBy('created_at', 'desc');

        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

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

        if (!empty($salaryMin)) {
            $query->where('salary_max', '>=', (int) $salaryMin);
        }

        if (!empty($salaryMax)) {
            $query->where('salary_min', '<=', (int) $salaryMax);
        }

        if (!empty($minEducation)) {
            $educationRank = EducationLevel::rankOf($minEducation);
            $query->whereHas('criteria', function ($q) use ($educationRank) {
                $q->whereRaw(
                    'CASE '
                    . "WHEN min_education = 'SMA' THEN 1 "
                    . "WHEN min_education = 'D3' THEN 2 "
                    . "WHEN min_education = 'D4' THEN 3 "
                    . "WHEN min_education = 'S1' THEN 4 "
                    . "WHEN min_education = 'S2' THEN 5 "
                    . "WHEN min_education = 'S3' THEN 6 "
                    . 'ELSE 0 END >= ?', [$educationRank]
                );
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Business Logic: Get vacancies paginated dengan categories
     */
    public function getVacanciesPaginated(
        ?string $searchQuery,
        ?string $categoryId,
        ?string $jobType,
        ?int $perPage,
        ?string $salaryMin = null,
        ?string $salaryMax = null,
        ?string $minEducation = null,
    ): array {
        $filters = [
            'searchQuery' => strtolower($searchQuery),
            'categoryId' => $categoryId,
            'jobType' => $jobType,
            'batchId' => null,
            'salaryMin' => $salaryMin,
            'salaryMax' => $salaryMax,
            'minEducation' => $minEducation,
        ];

        $jobs = $this->getByFiltersAndPaginated($filters, $perPage);

        $candidateId = auth('candidate')->id();
        $appliedJobIds = $candidateId
            ? \App\Models\Apply::where('candidate_id', $candidateId)->pluck('job_id')->toArray()
            : [];

        return [
            'jobs'       => $jobs,
            'categories' => $this->categoryRepo->getAll(),
            'educationLevels' => EducationLevel::cases(),
            'appliedJobIds' => $appliedJobIds,
        ];
    }

    /**
     * Business Logic: Get vacancy detail untuk display
     */
    public function findVacancyForDisplay(string $uuid, ?int $candidateId = null): array
    {
        $job = $this->findByUuid($uuid);
        abort_if(!$job, 404);
        $job->loadMissing('criteria');
        $appliesTotal = $job->applies()->count();

        return [
            'job'                  => $job,
            'activeBatch'          => $job->batch,
            'formattedAppliesTotal' => $appliesTotal < 10 ? '0' . $appliesTotal : $appliesTotal,
            'applyEligibility'     => $this->getApplyEligibility($job, $candidateId),
        ];
    }

    public function getApplyEligibility(Job $job, ?int $candidateId): array
    {
        $job->loadMissing('criteria');

        $eligibility = [
            'can_apply' => true,
            'already_applied' => false,
            'batch_expired' => false,
            'education_not_met' => false,
            'profile_incomplete' => false,
            'min_education_label' => EducationLevel::labelOf($job->criteria?->min_education),
            'candidate_education_label' => null,
        ];

        $job->loadMissing('batch');
        if ($job->batch && $job->batch->end_date < now()) {
            $eligibility['can_apply'] = false;
            $eligibility['batch_expired'] = true;

            return $eligibility;
        }

        if ($candidateId === null) {
            return $eligibility;
        }

        if ($this->applicationRepo->candidateHasApplied($candidateId, $job)) {
            $eligibility['can_apply'] = false;
            $eligibility['already_applied'] = true;

            return $eligibility;
        }

        $candidate = $this->candidateRepo->find($candidateId);
        $candidate?->load('profile');
        $candidateLevel = $candidate?->profile?->education_level;

        if (!$candidateLevel) {
            $eligibility['can_apply'] = false;
            $eligibility['profile_incomplete'] = true;

            return $eligibility;
        }

        $eligibility['candidate_education_label'] = EducationLevel::labelOf($candidateLevel);

        if (!$job->candidateMeetsEducation($candidateLevel)) {
            $eligibility['can_apply'] = false;
            $eligibility['education_not_met'] = true;
        }

        return $eligibility;
    }

    /**
     * Business Logic: Get vacancy apply form data
     */
    public function findVacancyApplyFormData(string $uuid, int $candidateId): array
    {
        $job = $this->findByUuid($uuid);
        abort_if(!$job, 404);
        $appliesTotal = $job->applies()->count();

        $existingApply = $this->applicationRepo->candidateHasApplied($candidateId, $job);
        if ($existingApply) {
            return ['already_applied' => true];
        }

        $eligibility = $this->getApplyEligibility($job, $candidateId);

        if ($eligibility['profile_incomplete']) {
            return ['profile_incomplete' => true];
        }

        if ($eligibility['education_not_met']) {
            return [
                'education_not_met' => true,
                'min_education_label' => $eligibility['min_education_label'],
                'candidate_education_label' => $eligibility['candidate_education_label'],
            ];
        }

        return [
            'job'                   => $job,
            'activeBatch'           => $job->batch,
            'formattedAppliesTotal' => $appliesTotal < 10 ? '0' . $appliesTotal : $appliesTotal,
            'candidate'             => $this->candidateRepo->findWithDocuments($candidateId),
        ];
    }
}