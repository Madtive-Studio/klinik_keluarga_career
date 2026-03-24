<?php

namespace App\Http\Controllers\Candidate\Jobs;

use App\Enums\JobType;
use App\Http\Controllers\Controller;
use App\Services\VacancyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacancyController extends Controller
{
    public function __construct(
        private VacancyService $service
    ) {}

    /**
     * Daftar semua lowongan pekerjaan
     * GET /candidate/jobs/vacancies
     */
    public function index(Request $request)
    {
        $data = $this->service->getVacancyListPaginated(
            $request->get('q'),
            $request->get('category') == 'SEMUA' ? '' : $request->get('category'),
            $request->get('job_type') == 'SEMUA' ? '' : $request->get('job_type'),
            $request->get('per_page', 10)
        );

        if ($request->ajax()) {
            $jobs = $data['jobs'];
            
            return response()->json([
                'html'       => view('candidate.jobs.vacancies.section._job_list', $data)->render(),
                'pagination' => (string) $jobs->appends($request->query())->links('pagination::bootstrap-4'),
                'total'      => $jobs->total() ?? 0,
                'firstItem'  => $jobs->firstItem() ?? 0,
                'lastItem'   => $jobs->lastItem() ?? 0,
            ]);
        }

        $jobTypes = JobType::getWithLabels();

        return view('candidate.jobs.vacancies.index', [
            'jobTypes' => $jobTypes,
            ...$data,
        ]);
    }

    /**
     * Detail satu lowongan pekerjaan
     * GET /candidate/jobs/vacancies/{uuid}
     */
    public function show(string $uuid)
    {
        $data = $this->service->getVacancyDetail($uuid);

        return view('candidate.jobs.vacancies.detail', $data);
    }

    public function apply(string $uuid)
    {
        $resultData = $this->service->getVacanyAppliesFormData(
            $uuid,
            Auth::guard('candidate')->id(),
        );

        if (isset($resultData['already_applied'])) {
            return redirect()->route('candidate.jobs.vacancies.show', $uuid)->with('warning', 'Kamu sudah melamar pekerjaan ini. Silakan cek halaman <a href="' . route('candidate.my.applies') . '"><u>Lamaran Saya</u></a> untuk melihat status lamaran kamu.');
        }

        return view('candidate.jobs.vacancies.apply', $resultData);
    }
}