<?php
namespace App\Repositories;

use App\Models\Candidate;
use App\Models\Job;

class JobRepository
{
    public function findByUuid($uuid) {
        return Job::with(['category', 'batch', 'applies'])->where('uuid', $uuid)->first();
    }

    public function getFilteredJobsPaginated(string $searchQuery = null, int $categoryId = null, string $jobType = null, int $batchId)
    {
        $query = Job::query()->with(['category', 'batch'])->where('batch_id', $batchId);

        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'LIKE', '%' . $searchQuery . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchQuery . '%');
            });
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($jobType)) {
            $query->where('type', $jobType);
        }

        return $query->paginate(10);
    }
}