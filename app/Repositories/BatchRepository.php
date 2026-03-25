<?php
namespace App\Repositories;

use App\Models\Batch;
use App\Models\Candidate;

class BatchRepository
{
    public function getActiveBatch()
    {
        return Batch::where('status', 'ACTIVE')->first();
    }
}