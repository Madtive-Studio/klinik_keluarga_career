<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobRequest;
use App\Enums\JobType;
use App\Models\Apply;
use App\Models\Job;
use App\Models\Batch;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class JobManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.jobs.index', [

        ]);
    }

    public function datatables()
    {
        $query = Job::with(['batch', 'category'])->orderBy('id', 'ASC');
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
            ->editColumn('salary', function ($row) {
                return formatSalaryShort($row->salary);
            })
            ->editColumn('type', function ($row) {
                return JobType::tryBadge($row->type);
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
            ->rawColumns(['action', 'is_show_salary', 'type'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $code = '#'.strtoupper(substr(uniqid(), 0, 10));
        $batches = Batch::orderBy('created_at', 'DESC')->get();
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
        $job = Job::create($request->safe()->only([
            'uuid', 'code', 'batch_id', 'category_id', 'title', 'type', 'quota',
            'salary', 'experience', 'qualification', 'description',
        ]) + [
            'user_id' => auth()->user()->id,
            'is_show_salary' => $request->input('is_show_salary') === '1',
        ]);

        $job->criteria()->create($request->criteriaAttributes());

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
        $batches = Batch::orderBy('created_at', 'DESC')->get();
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

        $job->update($request->safe()->only([
            'uuid', 'code', 'batch_id', 'category_id', 'title', 'type', 'quota',
            'salary', 'experience', 'qualification', 'description',
        ]) + [
            'user_id' => auth()->user()->id,
            'is_show_salary' => $request->input('is_show_salary') === '1',
        ]);

        $job->criteria()->updateOrCreate(
            ['job_id' => $job->id],
            $request->criteriaAttributes()
        );

        return redirect()->route('admin.jobs.index')->with('success', __('messages.admin.job.updated'));
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
