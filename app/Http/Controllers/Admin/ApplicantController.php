<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\ScoreRecommendation;
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
    public static function statusLabels(): array
    {
        return [
            'IN REVIEW' => __('admin.apply_status.in_review'),
            'NOT SUITABLE' => __('admin.apply_status.not_suitable'),
            'SHORTLISTED' => __('admin.apply_status.shortlisted'),
            'HIRED' => __('admin.apply_status.hired'),
        ];
    }

    public function index(Request $request)
    {
        $status = $request->get('status');

        return view('admin.applies.index', [
            'status' => $status,
            'statuses' => self::statusLabels(),
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

        $query = $query->with(['candidate', 'job', 'batch', 'applyDocuments.document'])->orderByDesc('auto_score')->orderBy('created_at', 'ASC');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('job.title', function ($row) {
                return $row->job->code . ' - ' . $row->job->title;
            })
            ->editColumn('document.name', function ($row) {
                $docs = $row->applyDocuments;
                if ($docs->isEmpty()) {
                    return '-';
                }
                if ($docs->count() === 1) {
                    return $docs->first()->document?->name ?? '-';
                }
                return __('admin.applies.documents_count', ['count' => $docs->count()]);
            })
            ->editColumn('auto_score', function ($row) {
                return $row->auto_score !== null ? $row->auto_score . '/100' : '-';
            })
            ->editColumn('score_recommendation', function ($row) {
                if (!$row->score_recommendation) {
                    return '-';
                }

                $recommendation = ScoreRecommendation::from($row->score_recommendation);

                return '<span class="badge ' . $recommendation->badgeClass() . '">' . $recommendation->label() . '</span>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group" aria-label="Basic example">';
                $btn .= '<a href="'.route('admin.applies.show', $row->id).'" class="btn btn-sm btn-primary detail"><i class="ti ti-eye"></i></a>';
                $btn .= '</div>';

                return $btn;
            })
            ->rawColumns(['action', 'score_recommendation'])
            ->make(true);
    }

    public function show($id)
    {
        $apply = Apply::with(['candidate.profile', 'candidate.skills', 'job.criteria', 'applyDocuments.document'])->findOrFail($id);
        return view('admin.applies.detail', [
            'uuid' => (string)Str::uuid(),
            'apply' => $apply,
            'statuses' => self::statusLabels(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $apply = Apply::findOrFail($id);
        $apply->update(['status' => $request->status]);

        $candidate = Candidate::where('id', $apply->candidate_id)->first();
        $job = Job::with(['category', 'batch'])->where('id', $apply->job_id)->first();

        if ($candidate && $job) {
            $statusLabel = self::statusLabels()[$request->status] ?? (string) $request->status;
            $candidate->notify(new ApplicationStatusUpdatedNotification($candidate, $job, $statusLabel));
        }

        return redirect()->route('admin.applies.index')->with('success', __('messages.admin.apply.status_updated'));
    }
}
