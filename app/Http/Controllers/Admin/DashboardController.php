<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apply;
use App\Models\Batch;
use App\Models\Job;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $activeBatch = Batch::where('status', 'ACTIVE')->first() ?? [];
        $jobList = Job::whereNotNull('uuid')->whereNotNull('batch_id')->count();
        $applicants = Apply::whereNotNull('batch_id')->count();
        $hired = Apply::whereNotNull('batch_id')->where('status', 'HIRED')->count();

        return view('admin.dashboard', [
            'activeBatch' => $activeBatch,
            'jobList' => $jobList,
            'applicants' => $applicants,
            'hired' => $hired,
        ]);
    }
}
