<?php

namespace App\Repositories;

use App\Enums\DocumentType;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

/**
 * DocumentRepository - Thick Repository Pattern
 * 
 * Handles both DB operations AND business logic for documents.
 * This is the bridge between Controller and Model.
 */
class DocumentRepository
{
    // ==================== DB Operations ====================

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

    // ==================== Business Logic ====================

    /**
     * Upload dokumen dari HTTP request
     * Menangani file upload, path generation, dan penyimpanan
     */
    public function uploadDocument($file, DocumentType $type): string
    {
        $path = $type->getPath();
        $fileName = generateFileName($type->value, $file->extension());
        
        return $file->storeAs($path, $fileName, 'public');
    }

    /**
     * Store dokumen dari form request dengan validasi
     * Business logic: upload file, generate metadata, simpan ke DB
     */
    public function store(object $data): bool
    {
        $file = $data->file('file');
        $type = DocumentType::from($data->type);
        
        if (!$file || !$file->isValid()) {
            throw new \DomainException('File invalid');
        }
        
        $filePath = $this->uploadDocument($file, $type);
        
        $map = [
            'name' => $file->getClientOriginalName(),
            'file' => $filePath,
            'type' => $type->value,
            'candidate_id' => Auth::guard('candidate')->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        return $this->create($map);
    }

    /**
     * Get documents untuk candidate dengan pagination
     * Business logic: fetch dengan documents dan hitung total
     */
    public function getCandidateDocumentsPaginated(int $userId, int $perPage = 5, string $typeBy = '*')
    {
        $candidate = Document::query()
            ->where('candidate_id', $userId);
        
        if ($typeBy !== '*') {
            $candidate->where('type', $typeBy);
        }
        
        $paginated = $candidate->paginate($perPage);
        
        return (object) [
            'id' => $userId,
            'documents' => $paginated,
            'documents_count' => $paginated->total(),
        ];
    }
}