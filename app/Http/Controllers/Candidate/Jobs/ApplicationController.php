<?php

namespace App\Http\Controllers\Candidate\Jobs;

use App\Enums\ApplicationStatus;
use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationRequest;
use App\Models\Apply;
use App\Models\Candidate as CandidateModel;
use App\Models\Document;
use App\Models\Job;
use App\Notifications\ApplicationSubmittedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->get('per_page', 5);
        $candidateId = Auth::guard('candidate')->id();

        $query = Apply::with(['job.category', 'batch', 'document'])
            ->where('candidate_id', $candidateId);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $query->orderBy(
            'created_at',
            $request->get('sortedBy') === 'NEWEST' ? 'desc' : 'asc'
        );

        return view('candidate.jobs.applications.index', [
            'applies' => $query->paginate($perPage),
        ]);
    }

    public function store(ApplicationRequest $request): RedirectResponse
    {
        try {
            $uuid = $request->input('job_uuid');
            $candidateId = Auth::guard('candidate')->id();

            $job = Job::with('batch')->where('uuid', $uuid)->firstOrFail();

            $existingApplication = Apply::where('job_id', $job->id)
                ->where('batch_id', $job->batch_id)
                ->where('candidate_id', $candidateId)
                ->exists();

            if ($existingApplication) {
                return redirect()
                    ->route('candidate.jobs.vacancies.show', $uuid)
                    ->with('warning', 'Kamu sudah melamar lowongan pekerjaan ini');
            }

            $validated = $request->validated();
            $documentId = $this->resolveDocumentId($request, $validated, $candidateId);

            Apply::create([
                'uuid' => (string) Str::uuid(),
                'candidate_id' => $candidateId,
                'job_id' => $job->id,
                'batch_id' => $job->batch_id,
                'document_id' => $documentId,
                'cover_letter' => $validated['cover_letter'],
                'description' => $validated['description'],
                'status' => ApplicationStatus::IN_REVIEW->value,
            ]);

            $candidate = CandidateModel::find($candidateId);
            $candidate?->notify(new ApplicationSubmittedNotification($candidate, $job));

            return redirect()
                ->route('candidate.jobs.applications.success', $uuid)
                ->with('success', 'Lamaran berhasil dikirim');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function applySuccess(string $uuid): View|RedirectResponse
    {
        if (!Auth::guard('candidate')->check()) {
            return redirect()->route('candidate.login.form');
        }

        $candidateId = Auth::guard('candidate')->id();

        $apply = Apply::with(['job', 'candidate', 'job.category', 'batch', 'document'])
            ->whereHas('job', fn ($q) => $q->where('uuid', $uuid))
            ->where('candidate_id', $candidateId)
            ->first();

        if (!$apply) {
            return redirect()
                ->route('candidate.jobs.vacancies.index')
                ->with('warning', 'Data lamaran tidak ditemukan.');
        }

        return view('candidate.jobs.vacancies.apply-success', [
            'job' => $apply->job,
            'candidate' => $apply->candidate,
            'category' => $apply->job?->category,
            'batch' => $apply->batch,
        ]);
    }

    private function resolveDocumentId(ApplicationRequest $request, array $validated, int $candidateId): int
    {
        if (strtolower((string) $validated['type_of_document']) === 'upload') {
            $documentFile = $request->file('new_document');
            $fileName = time() . '_' . $documentFile->getClientOriginalName();
            $path = $documentFile->storeAs('documents', $fileName, 'public');

            $documentType = DocumentType::CV;

            $document = Document::create([
                'candidate_id' => $candidateId,
                'name' => $documentFile->getClientOriginalName(),
                'file' => $path,
                'type' => $documentType,
                'category' => $documentType->getCategory(),
                'status' => DocumentStatus::PENDING,
            ]);

            return $document->id;
        }

        return (int) $validated['document_id'];
    }
}
