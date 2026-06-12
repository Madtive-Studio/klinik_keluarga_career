<?php

namespace App\Http\Controllers\Candidate;

use App\Enums\JobType;
use App\Http\Controllers\Controller;
use App\Http\Resources\JobCollection;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function home(): View
    {
        $jobTypes = JobType::getWithLabels();
        $activeBatch = Batch::active()->first();

        if (!$activeBatch) {
            return view('candidate.home', [
                'jobTypes' => $jobTypes,
                'activeBatch' => null,
                'formattedBatch' => null,
                'categories' => Category::orderBy('name')->get(),
                'jobsByType' => [],
                'message' => 'Belum ada batch yang aktif',
            ]);
        }

        $jobsByType = [];

        foreach ($jobTypes as $jobType => $label) {
            $jobsByType[$jobType] = Job::withListingRelations()
                ->forBatch($activeBatch->id)
                ->ofType($jobType)
                ->latestFirst()
                ->limit(5)
                ->get()
                ->toArray();
        }

        $jobsByType['ALL'] = Job::withListingRelations()
            ->forBatch($activeBatch->id)
            ->latestFirst()
            ->limit(5)
            ->get()
            ->toArray();

        return view('candidate.home', [
            'jobTypes' => $jobTypes,
            'activeBatch' => $activeBatch,
            'formattedBatch' => $activeBatch->formatted_label,
            'categories' => Category::orderBy('name')->get(),
            'jobsByType' => $jobsByType,
        ]);
    }

    public function jobsByType(Request $request): JsonResponse
    {
        $activeBatch = Batch::active()->first();
        $jobType = $request->get('job_type');

        $jobs = Job::withListingRelations()
            ->forBatch($activeBatch?->id)
            ->ofType($jobType)
            ->latestFirst()
            ->limit(5)
            ->get();

        return (new JobCollection($jobs))
            ->additional([
                'html' => view('candidate.home.section._job_list', ['jobs' => $jobs])->render(),
            ])
            ->response();
    }
}
