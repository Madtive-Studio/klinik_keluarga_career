<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\CV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApplyController extends Controller
{
    public function myInterviews(Request $request)
    {
        $userId = Auth::guard('candidate')->id();
        $yearNow = date('Y');
        $orderBy = $request->get('urutkan') == 'Terlama' ? 'ASC' : 'DESC';

        $candidate = Candidate::with(['interviews' => function ($query) use ($yearNow, $orderBy) {
            $query->whereYear('created_at', $yearNow);
            $query->orderBy('start_datetime', $orderBy);
        }])->where('id', $userId)
        ->first();

        $company = Company::first();

        return view('client.job-application.interview', [
            'candidate' => $candidate,
            'company' => $company,
            'interviews' => $candidate->interviews,
            'interviewsCount' => $candidate->interviews->count() < 10 ? '0' . $candidate->interviews->count() : $candidate->interviews->count(),
        ]);
    }

    public function myApplies(Request $request)
    {
        $status = $request->get('status');
        $userId = Auth::guard('candidate')->id();
        $yearNow = date('Y');

        $candidate = Candidate::with(['applies' => function ($query) use ($status, $yearNow) {
            if (!empty($status)) {
                $query->where('status', $status); 
            }
            $query->whereYear('created_at', $yearNow);
        }])->where('id', $userId)->first();

        return view('client.job-application.index', [
            'candidate' => $candidate,
            'applies' => $candidate->applies,
            'appliesCount' => $candidate->applies->count() < 10 ? '0' . $candidate->applies->count() : $candidate->applies->count(),
        ]);
    }
   
    public function myCV(Request $request)
    {
        $userId = Auth::guard('candidate')->id();
        $yearNow = date('Y');

        $candidate = Candidate::with(['CVs', 'applies' => function ($query) use ($yearNow) {
            $query->whereYear('created_at', $yearNow);
        }])->where('id', $userId)->first();

        return view('client.cv-saya.index', [
            'candidate' => $candidate,
            'cvs' => $candidate->cvs,
        ]);
    }

    public function createMyCV(Request $request)
    {
        return view('client.cv-saya.tambah');
    }

    public function storeMyCV(Request $request)
    {
        $rules = [
            'add_new_cv' => 'required|file|mimes:pdf,doc,docx|max:20480',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        
        if ($request->hasFile('add_new_cv') && $request->file('add_new_cv')->isValid()) {
            $file = $request->file('add_new_cv');
            $fileName = 'CV_'.time().'.'.$file->getClientOriginalExtension();
            $filePath = $file->storeAs('candidates', $fileName, 'public');

            CV::create([
                'name' => $file->getClientOriginalName(),
                'file' => $filePath,
                'candidate_id' => Auth::guard('candidate')->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Berhasil tambah file CV kamu')->withInput($request->all());
        } else {
            return redirect()->back()->with('error', 'Gagal mengupload file CV kamu')->withInput($request->all());
        }
    }
}
