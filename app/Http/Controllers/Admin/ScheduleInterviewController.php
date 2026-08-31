<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apply;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Batch;
use App\Models\Candidate;
use App\Models\Category;
use App\Models\Company;
use App\Models\ScheduleInterview;
use App\Notifications\InterviewInvitationNotification;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ScheduleInterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.schedule-interviews.index', [

        ]);
    }

    public function datatables(Request $request)
    {
        $query = ScheduleInterview::with(['batch', 'job', 'candidate', 'apply'])->orderBy('start_datetime', 'DESC');

        if ($request->filled('start_date')) {
            $query->whereDate('start_datetime', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('start_datetime', '<=', $request->end_date);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('start_datetime', function ($row) {
                return Carbon::parse($row->start_datetime)->format('d-m-Y');
            })
            ->editColumn('end_datetime', function ($row) {
                return Carbon::parse($row->end_datetime)->format('d-m-Y');
            })
            ->editColumn('link', function ($row) {
                return $row->is_online && $row->link ? '<a href="'.$row->link.'" target="_blank" class="btn btn-xs btn-outline-primary"><i class="ti ti-link me-1"></i>Link</a>' : '-';
            })
            ->editColumn('is_online', function ($row) {
                return $row->is_online
                    ? '<span class="badge bg-label-info"><i class="ti ti-video me-1"></i>Online</span>'
                    : '<span class="badge bg-label-secondary"><i class="ti ti-building me-1"></i>Offline</span>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group" aria-label="Action">';
                $btn .= '<a class="btn btn-sm btn-success invitation" href="'.route('admin.schedule-interviews.invitation', $row->id).'" title="Kirim Ulang Undangan"><i class="ti ti-mail"></i></a>';
                $btn .= '<button type="button" class="btn btn-sm btn-warning edit" data-route="'.route('admin.schedule-interviews.edit', $row->id).'" title="Edit Jadwal"><i class="ti ti-pencil"></i></button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['link', 'is_online', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $code = '#'.strtoupper(substr(uniqid(), 0, 10));
        $applies = Apply::with(['candidate', 'job.category', 'batch'])
            ->where('status', 'SHORTLISTED')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('admin.schedule-interviews.form', [
            'uuid' => (string)Str::uuid(),
            'code' => $code,
            'applies' => $applies,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $applyId = $request->apply_id;
        $applyData = Apply::where('id', $applyId)->first();

        if (!$applyData) {
            return redirect()->route('admin.schedule-interviews.index')->with('success', __('messages.admin.schedule_interview.invalid_apply'));
        }

        $data = $this->validatedScheduleInterviewData($request);
        $data['job_id'] = $applyData->job_id;
        $data['batch_id'] = $applyData->batch_id;
        $data['candidate_id'] = $applyData->candidate_id;
        $data['apply_id'] = $applyData->id;
        $data['is_online'] = $request->has('is_online');

        $interview = ScheduleInterview::create($data);
        $company = Company::first();

        $job = Job::with(['batch', 'category'])->where('id', $applyData->job_id)->first();
        $candidate = Candidate::where('id', $applyData->candidate_id)->first();
        $candidate->notify(new InterviewInvitationNotification($candidate, $job, $interview, $company));

        return redirect()->route('admin.schedule-interviews.index')->with('success', __('messages.admin.schedule_interview.created'));
    }

    public function invitation(Request $request, $id)
    {
        $interview = ScheduleInterview::where('id', $id)->first();
        $applyData = Apply::where('id', $interview->apply_id)->first();
        $candidate = Candidate::where('id', $applyData->candidate_id)->first();

        $job = Job::with(['batch', 'category'])->where('id', $applyData->job_id)->first();
        $company = Company::first();

        $candidate->notify(new InterviewInvitationNotification($candidate, $job, $interview, $company));
        return redirect()->route('admin.schedule-interviews.index')->with('success', __('messages.admin.schedule_interview.resent'));
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
        $scheduleInterview = ScheduleInterview::findOrFail($id);
        $applies = Apply::with(['candidate', 'job.category', 'batch'])
            ->where(function ($query) use ($scheduleInterview) {
                $query->where('status', 'SHORTLISTED')
                      ->orWhere('id', $scheduleInterview->apply_id);
            })
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('admin.schedule-interviews.form', [
            'scheduleInterview' => $scheduleInterview,
            'applies' => $applies,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $applyId = $request->apply_id;
        $applyData = Apply::where('id', $applyId)->first();

        if (!$applyData) {
            return redirect()->route('admin.schedule-interviews.index')->with('success', __('messages.admin.schedule_interview.invalid_apply'));
        }

        $data = $this->validatedScheduleInterviewData($request);
        $data['job_id'] = $applyData->job_id;
        $data['batch_id'] = $applyData->batch_id;
        $data['candidate_id'] = $applyData->candidate_id;
        $data['apply_id'] = $applyData->id;
        $data['is_online'] = $request->has('is_online');

        $scheduleInterview = ScheduleInterview::findOrFail($id);
        $scheduleInterview->update($data);

        $company = Company::first();
        $job = Job::with(['batch', 'category'])->where('id', $applyData->job_id)->first();
        $candidate = Candidate::where('id', $applyData->candidate_id)->first();
        $candidate?->notify(new InterviewInvitationNotification($candidate, $job, $scheduleInterview->fresh(), $company));

        return redirect()->route('admin.schedule-interviews.index')->with('success', __('messages.admin.schedule_interview.updated'));
    }

    private function validatedScheduleInterviewData(Request $request): array
    {
        $data = $request->validate([
            'uuid' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'apply_id' => ['required', 'integer', 'exists:applies,id'],
            'title' => ['required', 'string', 'max:255'],
            'start_datetime' => ['required', 'date_format:d-m-Y H:i:s'],
            'end_datetime' => ['required', 'date_format:d-m-Y H:i:s'],
            'link' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $data['start_datetime'] = parseFlatpickrDatetime($data['start_datetime']);
        $data['end_datetime'] = parseFlatpickrDatetime($data['end_datetime']);
        $data['link'] = $data['link'] ?? '';

        if (Carbon::parse($data['end_datetime'])->lte(Carbon::parse($data['start_datetime']))) {
            throw ValidationException::withMessages([
                'end_datetime' => 'End datetime tidak boleh sebelum atau sama dengan start datetime.',
            ]);
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $scheduleInterview = ScheduleInterview::findOrFail($id);
        if ($scheduleInterview) {
            $scheduleInterview->delete();
        }
        return redirect()->route('admin.schedule-interviews.index')->with('success', __('messages.admin.schedule_interview.deleted'));
    }
}
