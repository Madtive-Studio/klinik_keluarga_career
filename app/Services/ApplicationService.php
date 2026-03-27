<?php

namespace App\Services;

use App\Enums\DocumentType;
use App\Models\Apply;
use App\Models\Job;
use App\Notifications\ApplicationSubmittedNotification;
use App\Repositories\{ApplicationRepository, CandidateRepository, DocumentRepository, JobRepository};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ApplicationService
{
    public function __construct(
        private ApplicationRepository $applicationRepo,
        private CandidateRepository $candidateRepo,
        private JobRepository $jobRepo,
        private DocumentRepository $documentRepo,
    ) {}

    /**
     * Data untuk halaman daftar lamaran saya (paginated).
     */
    public function getApplicationsByCandidatePaginated(int $candidateId, int $per_page = 5, array $filters): object
    {
        $filters['sortedBy'] = $filters['sortedBy'] == 'NEWEST' ? 'DESC' : 'ASC';
        $applies = $this->applicationRepo->getApplicationsByCandidatePaginated($candidateId, $per_page, $filters);
        $applies->apply_count = $applies->total();

        return $applies;
    }

    /**
     * Proses submit lamaran
     */
    public function submitApplication(string $uuid, int $candidateId, array $requestData, ?UploadedFile $documentFile): array
    {
        $job = $this->jobRepo->findByUuid($uuid);

        if (!$job) {
            return ['error' => 'Data lowongan pekerjaan tidak ditemukan'];
        }

        if ($this->candidateHasApplied($candidateId, $job)) {
            return ['already_applied' => true, 'warning' => 'Kamu sudah melamar pekerjaan ini. Silakan cek halaman <a href="' . route('candidate.my.applications.index') . '">Lamaran Saya</a> untuk melihat status lamaran kamu.'];
        }

        $applyData = [
            'uuid'         => (string) Str::uuid(),
            'candidate_id' => $candidateId,
            'job_id'       => $job->id,
            'batch_id'     => $job->batch->id,
            'cover_letter' => $requestData['cover_letter'],
            'description'  => $requestData['description'],
            'status'       => 'IN REVIEW',
            'created_at'   => now(),
            'updated_at'   => now(),
        ];

        if (strtolower((string) $requestData['type_of_document']) === 'upload') {
            $documentId = $this->handleDocumentUpload($documentFile, $candidateId);
            if (!$documentId) {
                return ['error' => 'Gagal mengupload dokumen CV kamu'];
            }

            $applyData['document_id'] = $documentId;
        } else {
            $applyData['document_id'] = $requestData['document_id'];
        }

        $apply = $this->applicationRepo->create($applyData);

        $candidate = $this->candidateRepo->find($candidateId);
        $candidate->notify(new ApplicationSubmittedNotification($candidate, $job));

        return ['success' => $apply, 'candidate' => $candidate];
    }

    public function findApplicationByJobUuidAndCandidate(string $jobUuid, int $candidateId): ?Apply
    {
        return $this->applicationRepo->findApplicationByJobUuidAndCandidate($jobUuid, $candidateId);
    }

    private function handleDocumentUpload(?UploadedFile $file, int $candidateId): ?int
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $type = DocumentType::CV;
        $fileName = generateFileName($type->value, $file->extension());
        $filePath = $file->storeAs($type->getPath(), $fileName, 'public');

        $document = $this->documentRepo->createFromUpload([
            'name'         => $file->getClientOriginalName(),
            'file'         => $filePath,
            'type'         => $type->value,
            'candidate_id' => $candidateId,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return $document->id ?? null;
    }

    public function candidateHasApplied(int $candidateId, Job $job): bool
    {
        return (bool) $this->applicationRepo->findByJobBatchAndCandidate($job->id, $job->batch->id, $candidateId);
    }
}
