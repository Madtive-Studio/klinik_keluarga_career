<?php

namespace App\Http\Controllers\Candidate;

use App\Enums\JobType;
use App\Http\Controllers\Controller;
use App\Repositories\HomeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        private HomeRepository $homeRepository,
    ) {}

    public function home()
    {
        $jobTypes = JobType::getWithLabels();
        $data = $this->homeRepository->getHomeDisplayData($jobTypes);

        return view('candidate.home', [
            'jobTypes' => $jobTypes,
            ...$data,
        ]);
    }

    public function jobsByType(Request $request): JsonResponse
    {
        $jobs = $this->homeRepository->getJobsByTypeForHome($request->get('job_type'));

        return response()->json([
            'html' => view('candidate.home.section._job_list', ['jobs' => $jobs])->render(),
        ]);
    }
}
