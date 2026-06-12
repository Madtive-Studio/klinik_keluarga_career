<?php

namespace App\Http\Controllers\Candidate\Jobs;

use App\Enums\JobType;
use App\Http\Controllers\Controller;
use App\Http\Resources\JobCollection;
use App\Models\Apply;
use App\Models\Batch;
use App\Models\Candidate;
use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VacancyController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $searchQuery = $request->get('q');
        $categoryId = $request->get('category') === 'SEMUA' ? null : $request->get('category');
        $jobType = $request->get('job_type') === 'SEMUA' ? null : $request->get('job_type');
        $perPage = (int) $request->get('per_page', 10);

        $activeBatch = Batch::active()->first();

        $jobs = Job::withListingRelations()
            ->forBatch($activeBatch?->id)
            ->search($searchQuery)
            ->ofCategory($categoryId)
            ->ofType($jobType)
            ->latestFirst()
            ->paginate($perPage);

        if ($request->ajax()) {
            return (new JobCollection($jobs))
                ->additional([
                    'html' => view('candidate.jobs.vacancies.section._job_list', ['jobs' => $jobs])->render(),
                    'pagination' => (string) $jobs->appends($request->query())->links('pagination::bootstrap-4'),
                    'meta' => [
                        'total' => $jobs->total(),
                        'first_item' => $jobs->firstItem(),
                        'last_item' => $jobs->lastItem(),
                    ],
                ])
                ->response();
        }

        return view('candidate.jobs.vacancies.index', [
            'jobTypes' => JobType::getWithLabels(),
            'jobs' => $jobs,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function show(string $uuid): View
    {
        $job = Job::withListingRelations()
            ->with('applies')
            ->findByUuid($uuid)
            ->firstOrFail();

        return view('candidate.jobs.vacancies.detail', [
            'job' => $job,
            'activeBatch' => Batch::active()->first(),
            'appliesCount' => str_pad((string) ($job->applies->count() ?? 0), 2, '0', STR_PAD_LEFT),
        ]);
    }

    public function apply(string $uuid): View|RedirectResponse
    {
        $candidateId = Auth::guard('candidate')->id();

        $job = Job::withListingRelations()
            ->with('applies')
            ->findByUuid($uuid)
            ->firstOrFail();

        $alreadyApplied = Apply::where('job_id', $job->id)
            ->where('candidate_id', $candidateId)
            ->exists();

        if ($alreadyApplied) {
            return redirect()
                ->route('candidate.jobs.vacancies.show', $uuid)
                ->with('warning', 'Kamu sudah melamar pekerjaan ini. Silakan cek halaman <a href="' . route('candidate.my.applications.index') . '"><u>Lamaran Saya</u></a> untuk melihat status lamaran kamu.');
        }

        $candidate = Candidate::with('documents')->find($candidateId);

        return view('candidate.jobs.vacancies.apply', [
            'job' => $job,
            'activeBatch' => Batch::active()->first(),
            'appliesCount' => str_pad((string) ($job->applies->count() ?? 0), 2, '0', STR_PAD_LEFT),
            'candidate' => $candidate,
        ]);
    }
}
