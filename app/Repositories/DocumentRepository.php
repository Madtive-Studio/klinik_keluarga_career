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

    public function delete($id): bool
    {
        $document = Document::find($id);
        
        if (!$document) {
            return false;
        }
        
        return $document->delete();
    }
}