<?php
namespace App\Services;

use App\Enums\DocumentType;
use App\Repositories\CandidateRepository;
use App\Repositories\DocumentRepository;
use Illuminate\Support\Facades\Auth;

class DocumentService
{
    public function __construct(
        protected DocumentRepository $documentRepository,
        protected CandidateRepository $candidateRepository
    ) {}

    public function getCandidateDocuments($userId, $year)
    {
        $candidate = $this->candidateRepository->findWithDocuments($userId);
        $candidate->documents_count = $candidate->documents->count();

        return $candidate;
    }

    public function getCandidateDocumentsPaginated($userId, $perPage = 5, $typeBy = '*')
    {
        $candidate = $this->candidateRepository->getWithDocumentsPaginated($userId, $perPage, $typeBy);
        $candidate->documents_count = $candidate->documents->total();

        return $candidate;
    }

    public function uploadDocument($file, DocumentType $type)
    {
        $path = $type->getPath(); 
        $fileName = generateFileName($type->value, $file->extension());
        
        return $file->storeAs($path, $fileName, 'public');
    }

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
        
        return $this->documentRepository->create($map);
    }

    public function delete($id): bool
    {
        $document = $this->documentRepository->delete($id);
        
        if (!$document) {
            throw new \DomainException('Dokumen tidak ditemukan');
        }
        
        return $document;
    }
}