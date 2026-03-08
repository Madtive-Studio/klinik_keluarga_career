<?php

namespace App\Http\Controllers\Candidate\Jobs;

use App\Http\Controllers\Controller;
use App\Models\Apply;
use App\Models\Batch;
use App\Models\Candidate;
use App\Models\CV;
use App\Models\Job;
use App\Notifications\ApplicationSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function apply(Request $request, $Uuid)
    {
        $activeBatch = Batch::where('status', 'ACTIVE')->first();
        $job = Job::with(['category', 'batch', 'applies'])->where('uuid', $Uuid)->first();
        
        $appliesTotal = $job->applies()->count();
        $formattedAppliesTotal = $appliesTotal < 10 ? '0' . $appliesTotal : $appliesTotal;

        $hasCandidateApplied = Apply::where('job_id', $job->id)
                                        ->where('batch_id', $job->batch->id)
                                        ->where('candidate_id', Auth::guard('candidate')->id())
                                        ->first();

        $candidateCVs = CV::where('candidate_id', Auth::guard('candidate')->id())->get();
        // if ($hasCandidateApplied) {
        //     return redirect()->route('candidate.jobs.vacancies.detail', [$Uuid])->with('has_applied', 'Kamu sudah melamar lowongan pekerjaan ini');
        // }

        return view('candidate.jobs.vacancies.apply', [
            'job' => $job,
            'candidateCVs' => $candidateCVs,
            'activeBatch' => $activeBatch,
            'formattedAppliesTotal' => $formattedAppliesTotal
        ]);
    }

    public function applyProcess(Request $request, $Uuid)
    {
        $rules = [
            'type_of_cv' => 'required',
            'cover_letter' => 'required',
            'description' => 'required'
        ];

        if (strtoupper($request->type_of_cv) === 'UPLOAD') {
            $rules['new_cv'] = 'required|file|mimes:pdf,doc,docx|max:20480';
        } else {
            $rules['cv_id'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $job = Job::with(['category', 'batch'])->where('uuid', $Uuid)->first();

        if (!$job) {
            return redirect()->back()->with('error', 'Data lowongan pekerjaan tidak ditemukan');
        }

        $hasCandidateApplied = Apply::where('job_id', $job->id)
                                        ->where('batch_id', $job->batch->id)
                                        ->where('candidate_id', Auth::guard('candidate')->id())
                                        ->first();
        if ($hasCandidateApplied) {
            return redirect()->route('candidate.jobs.vacancies.detail', [$Uuid])->with('has_applied', 'Kamu sudah melamar lowongan pekerjaan ini');
        }

        $applyData = [
            'uuid' => (string) Str::uuid(),
            'candidate_id' => Auth::guard('candidate')->id(),
            'job_id' => $job->id,
            'batch_id' => $job->batch->id,
            'cover_letter' => $request->cover_letter,
            'status' => 'IN REVIEW',
            'description' => $request->description,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (strtoupper($request->type_of_cv) === 'UPLOAD') {
            if ($request->hasFile('new_cv') && $request->file('new_cv')->isValid()) {
                $file = $request->file('new_cv');
                $fileName = 'CV_'.time().'.'.$file->getClientOriginalExtension();
                $filePath = $file->storeAs('candidates', $fileName, 'public');

                $createCV = CV::create([
                    'name' => $file->getClientOriginalName(),
                    'file' => $filePath,
                    'candidate_id' => Auth::guard('candidate')->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $applyData['cv_id'] = $createCV->id;
            } else {
                return redirect()->back()->with('error', 'Gagal mengupload file CV kamu')->withInput($request->all());
            }
        } else {
            $applyData['cv_id'] = $request->cv_id;
        }

        $data['success'] = Apply::create($applyData);

        $candidate = Candidate::where('id', Auth::guard('candidate')->id())->first();
        $candidate->notify(new ApplicationSubmittedNotification($candidate, $job));

        return redirect()->route('candidate.jobs.applications.success', [$Uuid])->with($data);
    }

    public function applySuccess(Request $request, $Uuid)
    {
        return view('candidate.job.applications.success', [$Uuid]);
    }
}
