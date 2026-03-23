<?php

namespace App\Repositories;

use App\Models\Document;

class DocumentRepository
{
    public function create(array $data): bool
    {
        $result = Document::create($data);
        return $result ? true : false;
    }

    public function createFromUpload(array $data): Document
    {
        return Document::create($data);
    }

    public function delete($id): bool
    {
        $document = Document::find($id);

        if (!$document) {
            return false;
        }

        return $document->delete();
    }

    public function findCVsByCandidate(int $candidateId)
    {
        return Document::where('candidate_id', $candidateId)
                       ->where('type', 'CV')
                       ->get();
    }
}