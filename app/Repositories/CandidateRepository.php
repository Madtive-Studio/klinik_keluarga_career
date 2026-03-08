<?php
namespace App\Repositories;

use App\Models\Candidate;

class CandidateRepository
{
    public function findWithDocuments($userId)
    {
        return Candidate::with(['documents'])->where('id', $userId)->first();
    }
}