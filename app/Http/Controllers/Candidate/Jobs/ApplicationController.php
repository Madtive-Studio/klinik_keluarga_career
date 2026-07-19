<?php

namespace App\Http\Controllers\Candidate\Jobs;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationRequest;
use App\Repositories\ApplicationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function __construct(
        private ApplicationRepository $repository,
    ) {}

    /**
     * Daftar lamaran milik kandidat
     * GET /candidate/jobs/applications
     */
    public function index(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'sortedBy' => $request->get('sortedBy'),
        ];
        $per_page = $request->get('per_page', 5);
        $data = $this->repository->getApplicationsByCandidatePaginatedFormatted(
            Auth::guard('candidate')->id(),
            $per_page,
            $filters
        );

        return view('candidate.jobs.applications.index', [
            'applies' => $data,
        ]);
    }

    /**
     * Proses submit lamaran
     * POST /candidate/jobs/applications (job_uuid di body)
     */
    public function store(ApplicationRequest $request)
    {
        $uuid = $request->input('job_uuid');
        $resultData = $this->repository->submitApplication(
            $uuid,
            Auth::guard('candidate')->id(),
            $request->validated(),
            $request->file('new_document'), 
        );

        if (isset($resultData['error'])) {
            return redirect()->back()->with('error', $resultData['error'])->withInput();
        }

        if (isset($resultData['already_applied'])) {
            return redirect()->route('candidate.jobs.vacancies.show', $uuid)->with('warning', __('messages.application.already_applied'));
        }

        if (isset($resultData['education_not_met'])) {
            return redirect()->route('candidate.jobs.vacancies.show', $uuid)->with('warning', $resultData['error']);
        }

        return redirect()->route('candidate.jobs.applications.success', $uuid)->with($resultData);
    }

    /**
     * Halaman sukses setelah melamar
     * GET /candidate/jobs/{uuid}/success
     */
    public function applySuccess($uuid)
    {
        if (!Auth::guard('candidate')->check()) {
            return redirect()->route('candidate.login.form');
        }

        $apply = $this->repository->findApplicationByJobUuidAndCandidate($uuid, Auth::guard('candidate')->id());
        if (!$apply) {
            return redirect()->route('candidate.jobs.vacancies.index')
                ->with('warning', __('messages.application.not_found'));
        }

        return view('candidate.jobs.vacancies.apply-success', [
            'job' => $apply->job,
            'candidate' => $apply->candidate,
            'category' => $apply->job?->category,
            'batch' => $apply->batch,
        ]);
    }
}