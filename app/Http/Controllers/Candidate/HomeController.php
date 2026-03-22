<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $activeBatch = Batch::where('status', 'ACTIVE')->first();
        if ($activeBatch) {
            $formattedBatch = sprintf('%s - %s - | %s - %s', 
                $activeBatch->code, 
                $activeBatch->name,
                Carbon::parse($activeBatch->start_date)->translatedFormat('d F Y'),
                Carbon::parse($activeBatch->end_date)->translatedFormat('d F Y'),
            );
            $jobsByType = [
                'All' => Job::where('batch_id', $activeBatch->id)->latest()->limit(5)->get(),
                'WFH/Remote' => Job::where('batch_id', $activeBatch->id)->where('type', 'WFH/Remote')->latest()->limit(5)->get(),
                'Partime/Freelancer' => Job::where('batch_id', $activeBatch->id)->where('type', 'Partime/Freelancer')->latest()->limit(5)->get(),
                'Fulltime/Onsite' => Job::where('batch_id', $activeBatch->id)->where('type', 'Fulltime/Onsite')->latest()->limit(5)->get(),
                'Internship' => Job::where('batch_id', $activeBatch->id)->where('type', 'Internship')->latest()->limit(5)->get(),
            ];
        } else {
            $formattedBatch = "No active batch available";
            $jobsByType = [
                'All' => [],
                'WFH/Remote' => [],
                'Partime/Freelancer' => [],
                'Fulltime/Onsite' => [],
                'Internship' => [],
            ];
        }

        $categories = Category::orderBy('name', 'ASC')->get();
    
        return view('candidate.home', [
            'jobsByType' => $jobsByType,
            'activeBatch' => $activeBatch,
            'formattedBatch' => $formattedBatch,
            'categories' => $categories,
        ]);
    }
}
