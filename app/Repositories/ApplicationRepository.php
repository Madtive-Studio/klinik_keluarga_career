<?php

namespace App\Repositories;

use App\Enums\DocumentType;
use App\Models\Apply;
use App\Models\Candidate;
use App\Models\Job;
use App\Notifications\ApplicationSubmittedNotification;
use App\Repositories\CandidateRepository;
use App\Repositories\DocumentRepository;
use App\Services\ScoringService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApplicationRepository
{
    public function __construct(
        private DocumentRepository $documentRepo,
        private CandidateRepository $candidateRepo,
        private ScoringService $scoringService,
    ) {}

    public function findByJobBatchAndCandidate(int $jobId, int $batchId, int $candidateId): ?Apply
    {
        return Apply::where('job_id', $jobId)
                    ->where('batch_id', $batchId)
                    ->where('candidate_id', $candidateId)
                    ->first();
    }

    public function findApplicationByJobUuidAndCandidate(string $jobUuid, int $candidateId): ?Apply
    {
        return Apply::with(['job', 'candidate', 'job.category', 'batch'])
                    ->whereHas('job', function ($q) use ($jobUuid) {
                        $q->where('uuid', $jobUuid);
                    })
                    ->where('candidate_id', $candidateId)
                    ->first();
    }

    public function create(array $data): Apply
    {
        return Apply::create($data);
    }

    public function getApplicationsByCandidatePaginated(int $candidateId, int|string $perPage, array $filters): LengthAwarePaginator
    {
        $status = $filters['status'];
        $sortedBy = $filters['sortedBy'];

        return Apply::with(['job.category'])
                    ->where('candidate_id', $candidateId)
                    ->when($status, fn ($q) => $q->where('status', $status))
                    ->whereYear('created_at', date('Y'))
                    ->orderBy('created_at', $sortedBy)
                    ->paginate($perPage);
    }

    /**
     * Business Logic: Get applications by candidate paginated dengan format
     */
    public function getApplicationsByCandidatePaginatedFormatted(int $candidateId, int $per_page = 5, array $filters): object
    {
        $filters['sortedBy'] = $filters['sortedBy'] == 'NEWEST' ? 'DESC' : 'ASC';
        $applies = $this->getApplicationsByCandidatePaginated($candidateId, $per_page, $filters);
        $applies->apply_count = $applies->total();

        return $applies;
    }

    /**
     * Business Logic: Submit application
     */
    public function submitApplication(string $uuid, int $candidateId, array $requestData, ?UploadedFile $documentFile): array
    {
        $job = app(JobRepository::class)->findByUuid($uuid);

        if (!$job) {
            return ['error' => 'Data lowongan pekerjaan tidak ditemukan'];
        }

        if ($this->candidateHasApplied($candidateId, $job)) {
            return ['already_applied' => true, 'warning' => 'Kamu sudah melamar pekerjaan ini. Silakan cek halaman <a href="' . route('candidate.my.applications.index') . '">Lamaran Saya</a> untuk melihat status lamaran kamu.'];
        }

        $candidate = $this->candidateRepo->find($candidateId);
        $candidate?->load(['profile', 'skills']);

        if (!$candidate?->profile?->education_level) {
            return [
                'error' => 'Lengkapi profil kamu terlebih dahulu di halaman <a href="' . route('candidate.my.profile.edit') . '">Profil Saya</a> sebelum melamar.',
            ];
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

        $scoreResult = $this->scoringService->calculate(
            $candidate,
            $job,
            (string) $requestData['cover_letter']
        );

        $applyData['auto_score'] = $scoreResult['score'];
        $applyData['score_recommendation'] = $scoreResult['recommendation'];
        $applyData['score_breakdown'] = $scoreResult['breakdown'];
        $applyData['scored_at'] = now();

        $apply = $this->create($applyData);

        $candidate->notify(new ApplicationSubmittedNotification($candidate, $job));

        return ['success' => $apply, 'candidate' => $candidate];
    }

    public function candidateHasApplied(int $candidateId, Job $job): bool
    {
        return (bool) $this->findByJobBatchAndCandidate($job->id, $job->batch->id, $candidateId);
    }

    /**
     * Business Logic: Handle document upload untuk application
     */
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
}
