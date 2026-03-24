<?php

namespace App\Services;

use App\Enums\DocumentType;
use App\Models\Apply;
use App\Models\Job;
use App\Notifications\ApplicationSubmittedNotification;
use App\Repositories\{ApplicationRepository, BatchRepository, CandidateRepository, DocumentRepository, JobRepository};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ApplicationService
{
    public function __construct(
        private ApplicationRepository $applicationRepo,
        private BatchRepository $batchRepo,
        private CandidateRepository $candidateRepo,
        private JobRepository $jobRepo,
        private DocumentRepository $documentRepo,
    ) {}

    /**
     * Get data untuk halaman daftar lamaran saya
     */
    public function getMyApplications(int $candidateId, ?string $status): array
    {
        $applies = $this->applicationRepo->findByCandidateWithFilters($candidateId, $status, date('Y'));

        return [
            'applies'      => $applies,
            'appliesCount' => $applies->count() < 10 ? '0' . $applies->count() : $applies->count(),
        ];
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

        $hasApplied = $this->IsCandidateHasApplied($candidateId, $job);
        if ($hasApplied) {
            return ['already_applied' => true, 'warning' => 'Kamu sudah melamar pekerjaan ini. Silakan cek halaman <a href="' . route('candidate.my.applies') . '">Lamaran Saya</a> untuk melihat status lamaran kamu.'];
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

        if (strtoupper($requestData['type_of_document']) === 'UPLOAD') {
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

    public function getJobWithCandidate(string $jobUuid, int $candidateId): ?Apply
    {
        return $this->applicationRepo->findWithJobAndCandidate($jobUuid, $candidateId);
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

    public function IsCandidateHasApplied(int $candidateId, Job $job): bool
    {
        $alreadyApplied = $this->applicationRepo->findByJobBatchAndCandidate($job->id, $job->batch->id, $candidateId);
        if ($alreadyApplied) {
            return true;
        }
        
        return false;
    }
}