<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobImageUploadRequest;
use App\Http\Requests\JobRequest;
use App\Enums\EducationLevel;
use App\Enums\JobType;
use App\Models\Apply;
use App\Models\Job;
use App\Models\Batch;
use App\Models\Category;
use App\Services\JobImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class JobManagementController extends Controller
{
    public function __construct(
        private JobImageService $jobImageService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $batches = Batch::orderBy('created_at', 'DESC')->get();

        return view('admin.jobs.index', [
            'categories' => $categories,
            'batches' => $batches,
        ]);
    }

    public function datatables(Request $request)
    {
        $query = Job::with(['batch', 'category', 'criteria'])
            ->orderBy('id', 'ASC');

        if ($batchId = $request->get('batch_id')) {
            $query->where('batch_id', $batchId);
        }

        if ($categoryId = $request->get('category')) {
            $query->where('category_id', $categoryId);
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($salaryMin = $request->get('salary_min')) {
            $query->where('salary_max', '>=', (int) $salaryMin);
        }

        if ($salaryMax = $request->get('salary_max')) {
            $query->where('salary_min', '<=', (int) $salaryMax);
        }

        if ($minEducation = $request->get('min_education')) {
            $educationRank = EducationLevel::rankOf($minEducation);
            $query->whereHas('criteria', function ($q) use ($educationRank) {
                $q->whereRaw(
                    'CASE '
                    . "WHEN min_education = 'SMA' THEN 1 "
                    . "WHEN min_education = 'D3' THEN 2 "
                    . "WHEN min_education = 'D4' THEN 3 "
                    . "WHEN min_education = 'S1' THEN 4 "
                    . "WHEN min_education = 'S2' THEN 5 "
                    . "WHEN min_education = 'S3' THEN 6 "
                    . 'ELSE 0 END >= ?', [$educationRank]
                );
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('is_show_salary', function ($row) {
                $checked = $row->is_show_salary ? ' checked' : '';
                $stateLabel = $row->is_show_salary ? 'switch-on' : 'switch-off';

                return '<label class="switch switch-primary switch-sm mb-0">'
                    . '<input type="checkbox" class="switch-input toggle-show-salary" data-id="' . $row->id . '"' . $checked . '>'
                    . '<span class="switch-toggle-slider">'
                    . '<span class="' . $stateLabel . '"></span>'
                    . '</span>'
                    . '</label>';
            })
            ->addColumn('salary', function ($row) {
                return formatSalaryRange($row->salary_min, $row->salary_max, true);
            })
            ->editColumn('type', function ($row) {
                return JobType::tryBadge($row->type);
            })
            ->addColumn('min_education', function ($row) {
                $level = $row->relationLoaded('criteria') && $row->criteria
                    ? $row->criteria->min_education
                    : null;

                return $level ? EducationLevel::labelOf($level) : '-';
            })
            ->editColumn('quota', function ($row) {
                $applicants = Apply::where('job_id', $row->id)
                    ->where('batch_id', $row->batch_id)
                    ->count();

                return $applicants . '/' . (int) $row->quota;
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group" aria-label="Basic example">';
                $btn .= '<button type="button" class="btn btn-sm btn-warning edit" data-route="'.route('admin.jobs.edit', $row->id).'"><i class="ti ti-pencil"></i></button>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete" data-route="'.route('admin.jobs.destroy', $row->id).'"><i class="ti ti-trash"></i></button>';
                $btn .= '</div>';

                return $btn;
            })
            ->rawColumns(['action', 'is_show_salary', 'type', 'min_education'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $code = '#'.strtoupper(substr(uniqid(), 0, 10));
        $batches = Batch::available()->orderBy('created_at', 'DESC')->get();
        $categories = Category::orderBy('created_at', 'DESC')->get();

        return view('admin.jobs.form', [
            'uuid' => (string) Str::uuid(),
            'code' => $code,
            'batches' => $batches,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobRequest $request)
    {
        $imagePaths = $request->resolvedImagePaths();

        $attributes = $request->safe()->only([
            'uuid', 'code', 'batch_id', 'category_id', 'title', 'type', 'quota',
            'salary_min', 'salary_max', 'experience', 'qualification', 'description',
        ]) + [
            'user_id' => auth()->user()->id,
            'is_show_salary' => $request->input('is_show_salary') === '1',
            'images' => $imagePaths,
        ];

        try {
            DB::transaction(function () use ($request, $attributes) {
                $job = Job::create($attributes);
                $job->criteria()->create($request->criteriaAttributes());
            });
        } catch (\Throwable $exception) {
            $this->jobImageService->deletePaths($imagePaths);

            throw $exception;
        }

        return redirect()->route('admin.jobs.index')->with('success', __('messages.admin.job.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $job = Job::with('criteria')->findOrFail($id);
        $batches = Batch::available()->orderBy('created_at', 'DESC')->get();
        $currentBatch = $job->batch;
        if ($currentBatch && $currentBatch->end_date < now()) {
            $batches->push($currentBatch);
        }
        $categories = Category::orderBy('created_at', 'DESC')->get();

        return view('admin.jobs.form', [
            'job' => $job,
            'batches' => $batches,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobRequest $request, string $id)
    {
        $job = Job::findOrFail($id);
        $previousImages = $job->images ?? [];
        $requestedPaths = $request->resolvedImagePaths();
        $removedPaths = $this->jobImageService->removedJobImages($previousImages, $requestedPaths);
        $newPaths = $this->jobImageService->newlyAddedJobImages($previousImages, $requestedPaths);

        $attributes = $request->safe()->only([
            'uuid', 'code', 'batch_id', 'category_id', 'title', 'type', 'quota',
            'salary_min', 'salary_max', 'experience', 'qualification', 'description',
        ]) + [
            'user_id' => auth()->user()->id,
            'is_show_salary' => $request->input('is_show_salary') === '1',
            'images' => $this->jobImageService->finalizeJobImages($previousImages, $requestedPaths),
        ];

        try {
            DB::transaction(function () use ($request, $job, $attributes) {
                $job->update($attributes);
                $job->criteria()->updateOrCreate(
                    ['job_id' => $job->id],
                    $request->criteriaAttributes()
                );
            });

            $this->jobImageService->deletePaths($removedPaths);
        } catch (\Throwable $exception) {
            $this->jobImageService->deletePaths($newPaths);

            throw $exception;
        }

        return redirect()->route('admin.jobs.index')->with('success', __('messages.admin.job.updated'));
    }

    public function uploadImage(JobImageUploadRequest $request): JsonResponse
    {
        $path = $this->jobImageService->storeUpload(
            $request->file('image'),
            $request->input('job_uuid')
        );

        return response()->json([
            'path' => $path,
            'url' => \Illuminate\Support\Facades\Storage::url($path),
        ]);
    }

    public function destroyImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'job_uuid' => ['required', 'uuid'],
            'path' => ['required', 'string', 'max:255'],
        ]);

        $this->jobImageService->assertPathsBelongToJob([$validated['path']], $validated['job_uuid']);
        $this->jobImageService->deletePaths([$validated['path']]);

        return response()->json(['success' => true]);
    }

    public function toggleShowSalary(Request $request, string $id)
    {
        $request->validate([
            'is_show_salary' => ['required', 'boolean'],
        ]);

        $job = Job::findOrFail($id);
        $job->update([
            'is_show_salary' => $request->boolean('is_show_salary'),
        ]);

        return response()->json([
            'success' => true,
            'is_show_salary' => (bool) $job->is_show_salary,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job = Job::findOrFail($id);
        if ($job) {
            $job->delete();
        }
        return redirect()->route('admin.jobs.index')->with('success', __('messages.admin.job.deleted'));
    }
}
