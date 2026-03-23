<?php
namespace App\Repositories;

use App\Models\Candidate;

class CandidateRepository
{
    public function find($candidateId)
    {
        return Candidate::find($candidateId);
    }
    
    public function findWithDocuments($candidateId)
    {
        return Candidate::with(['documents'])->where('id', $candidateId)->first();
    }

    public function findWithDocumentsPaginated($candidateId, $perPage, $typeBy)
    {
        $candidate = Candidate::findOrFail($candidateId);
        
        $query = $candidate->documents()->orderBy('created_at', 'desc');
        
        if ($typeBy !== '*') {
            $query->where('type', $typeBy);
        }
        
        $documents = $query->paginate($perPage);
        
        $candidate->setRelation('documents', $documents);
        
        return $candidate;
    }
}