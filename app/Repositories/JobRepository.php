<?php
namespace App\Repositories;

use App\Models\Job;

class JobRepository
{
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
}