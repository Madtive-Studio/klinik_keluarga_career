<?php

namespace App\Http\Controllers\Candidate\Jobs;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationRequest;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function __construct(
        private ApplicationService $service,
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
        $data = $this->service->getMyApplicationsPaginated(
            Auth::guard('candidate')->id(),
            $per_page,
            $filters
        );

        return view('candidate.jobs.applications.index', [
            'applies' => $data,
        ]);
    }

    /**
     * Form lamaran pekerjaan
     * GET /candidate/jobs/
     */
    public function show(string $uuid)
    {
        
    }

    /**
     * Proses submit lamaran
     * POST /candidate/jobs/applications/{uuid}
     */
    public function store(ApplicationRequest $request)
    {
        $uuid = $request->input('job_uuid');
        $resultData = $this->service->submitApplication(
            $uuid,
            Auth::guard('candidate')->id(),
            $request->validated(),
            $request->file('new_document'), 
        );

        if (isset($resultData['error'])) {
            return redirect()->back()->with('error', $resultData['error'])->withInput();
        }

        if (isset($resultData['already_applied'])) {
            return redirect()->route('candidate.jobs.vacancies.show', $uuid)->with('warning', 'Kamu sudah melamar lowongan pekerjaan ini');
        }

        return redirect()->route('candidate.jobs.applications.success', $uuid)->with($resultData);
    }

    /**
     * Halaman sukses setelah melamar
     * GET /candidate/jobs/{uuid}/success
     */
    public function applySuccess($uuid)
    {
        $jobAppliedData = $this->service->getJobWithCandidate($uuid, Auth::guard('candidate')->id());
        return view('candidate.jobs.vacancies.apply-success', [
            'job' => $jobAppliedData,
            'candidate' => $jobAppliedData?->candidate,
            'category' => $jobAppliedData?->job?->category,
            'batch'=> $jobAppliedData?->batch,
        ]);
    }
}