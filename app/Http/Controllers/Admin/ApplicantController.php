<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apply;
use App\Models\Batch;
use App\Models\Candidate;
use App\Models\Category;
use App\Models\Job;
use App\Notifications\UpdateApplicateStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        return view('admin.applies.index', [
            'status' => $status
        ]);
    }

    public function datatables(Request $request)
    {
        $status = $request->get('status');
        $query = Apply::query();

        if (!empty($status)) {
            $query->where(function ($query) use ($status) {
                return $query->where('status', $status);
            });
        }

        $query = $query->with(['candidate', 'job', 'batch', 'cv'])->orderBy('created_at', 'ASC');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('job.title', function ($row) {
                return $row->job->code . ' - ' . $row->job->title;
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group" aria-label="Basic example">';
                $btn .= '<a href="'.Storage::url($row->cv->file).'" download class="btn btn-sm btn-info download"><i class="ti ti-download"></i></a>';
                $btn .= '<a href="'.route('admin.applies.show', $row->id).'" class="btn btn-sm btn-primary detail"><i class="ti ti-eye"></i></a>';
                $btn .= '</div>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        $apply = Apply::findOrFail($id);
        return view('admin.applies.detail', [
            'uuid' => (string)Str::uuid(),
            'apply' => $apply,
        ]);
    }

    public function update(Request $request, $id)
    {
        $apply = Apply::findOrFail($id);
        $apply->update(['status' => $request->status]);

        $candidate = Candidate::where('id', $apply->candidate_id)->first();
        $job = Job::where('id', $apply->job_id)->first();

        switch ($request->status) {
            case 'IN REVIEW':
                $status = "Sedang Dalam Review";
                $view = 'candidate.jobs.vacancies.in-review-email';
                break;
            case 'NOT SUITABLE':
                $status = "Tidak Sesuai";
                $view = 'candidate.jobs.vacancies.not-suitable-email';
                break;
            case 'SHORTLISTED':
                $status = "Lolos Tahap Selanjutnya";
                $view = 'candidate.jobs.vacancies.shortlisted-email';
                break;
            case 'HIRED':
                $status = "Diterima";
                $view = 'candidate.jobs.vacancies.hired-email';
                break;
            default:
                $view = null;
        }

        $candidate->notify(new UpdateApplicateStatusNotification($view, $candidate, $job, $status));
        return redirect()->route('admin.applies.index')->with('success', 'Berhasil mengubah status lamaran kandidat');
    }
}
