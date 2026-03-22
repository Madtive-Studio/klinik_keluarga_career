<?php

namespace App\Http\Controllers\Candidate\Jobs;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->get('q');
        $categoryId = $request->get('kategori');
        $jobType = $request->get('jenis');
        $jobs = Job::query();
        $activeBatch = Batch::where('status', 'ACTIVE')->first();
        $activeBatchId = $activeBatch->id ?? 0;

        if (empty($activeBatch)) {
            $activeBatchId = 0;
        }

        if (!empty($searchQuery)) {
            $jobs->where(function ($query) use ($searchQuery) {
                $query->where('title', 'LIKE', '%' . $searchQuery . '%')->orWhere('description', 'LIKE', '%' . $searchQuery . '%');
            });
        }

        if (!empty($categoryId)) {
            $jobs->where('category_id', $categoryId);
        }

        if (!empty($jobType)) {
            $jobs->where('type', $jobType);
        }

        $jobs = $jobs->with(['category', 'batch'])->where('batch_id', $activeBatchId)->paginate(10);
        $categories = Category::orderBy('name', 'ASC')->get();

        return view('candidate.jobs.vacancies.index', [
            'categories' => $categories,
            'jobs' => $jobs,
        ]);
    }

    public function detail(Request $request, $Uuid)
    {
        $activeBatch = Batch::where('status', 'ACTIVE')->first();
        $job = Job::with(['category', 'batch', 'applies'])->where('uuid', $Uuid)->first();

        $appliesTotal = $job->applies()->count();
        $formattedAppliesTotal = $appliesTotal < 10 ? '0' . $appliesTotal : $appliesTotal;

        return view('candidate.jobs.vacancies.detail', [
            'job' => $job,
            'activeBatch' => $activeBatch,
            'formattedAppliesTotal' => $formattedAppliesTotal,
        ]);
    }
}