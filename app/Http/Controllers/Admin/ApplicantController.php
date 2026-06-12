<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApplicantStatusRequest;
use App\Models\Apply;
use App\Models\Candidate;
use App\Models\Job;
use App\Notifications\ApplicationStatusUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.applies.index', [
            'status' => $request->get('status'),
        ]);
    }

    public function datatables(Request $request)
    {
        $status = $request->get('status');
        $query = Apply::query();

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $query = $query->with(['candidate', 'job', 'batch', 'document'])
            ->orderBy('created_at', 'ASC');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('job.title', function ($row) {
                return $row->job->code . ' - ' . $row->job->title;
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group" aria-label="Basic example">';
                $btn .= '<a href="'.Storage::url($row->document->file).'" download class="btn btn-sm btn-info download"><i class="ti ti-download"></i></a>';
                $btn .= '<a href="'.route('admin.applies.show', $row->id).'" class="btn btn-sm btn-primary detail"><i class="ti ti-eye"></i></a>';
                $btn .= '</div>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show(int $id)
    {
        $apply = Apply::findOrFail($id);

        return view('admin.applies.detail', [
            'uuid' => (string) Str::uuid(),
            'apply' => $apply,
        ]);
    }

    public function update(UpdateApplicantStatusRequest $request, int $id)
    {
        $apply = Apply::findOrFail($id);
        $status = ApplicationStatus::from($request->validated('status'));

        $apply->update(['status' => $status->value]);

        $candidate = Candidate::find($apply->candidate_id);
        $job = Job::with(['category', 'batch'])->find($apply->job_id);

        if ($candidate && $job) {
            $candidate->notify(new ApplicationStatusUpdatedNotification(
                $candidate,
                $job,
                $status->getLabel()
            ));
        }

        return redirect()
            ->route('admin.applies.index')
            ->with('success', 'Berhasil mengubah status lamaran kandidat');
    }
}
