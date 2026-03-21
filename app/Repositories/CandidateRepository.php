<?php
namespace App\Repositories;

use App\Models\Candidate;

class CandidateRepository
{
    public function findWithDocuments($userId)
    {
        return Candidate::with(['documents'])->where('id', $userId)->first();
    }

    public function findWithDocumentsPaginated($userId, $perPage, $typeBy)
    {
        $candidate = Candidate::findOrFail($userId);
        
        $query = $candidate->documents()->orderBy('created_at', 'desc');
        
        if ($typeBy !== '*') {
            $query->where('type', $typeBy);
        }
        
        $documents = $query->paginate($perPage);
        
        $candidate->setRelation('documents', $documents);
        
        return $candidate;
    }
}