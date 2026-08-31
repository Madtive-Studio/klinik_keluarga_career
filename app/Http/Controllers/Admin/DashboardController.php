<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apply;
use App\Models\Batch;
use App\Models\Candidate;
use App\Models\Category;
use App\Models\Job;
use App\Models\ScheduleInterview;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $activeBatch = Batch::where('status', 'ACTIVE')->first();
        $jobList = Job::whereNotNull('uuid')->whereNotNull('batch_id')->count();
        $applicants = Apply::whereNotNull('batch_id')->count();
        $hired = Apply::whereNotNull('batch_id')->where('status', 'HIRED')->count();

        $upcomingInterviews = ScheduleInterview::with(['candidate', 'job', 'batch'])
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime', 'ASC')
            ->limit(5)
            ->get();

        $chart = $this->buildMonthlyChartData();

        return view('admin.dashboard', [
            'activeBatch' => $activeBatch,
            'jobList' => $jobList,
            'applicants' => $applicants,
            'hired' => $hired,
            'upcomingInterviews' => $upcomingInterviews,
            'chartLabels' => $chart['labels'],
            'candidateSeries' => $chart['candidates'],
            'hiredSeries' => $chart['hired'],
        ]);
    }

    private function buildMonthlyChartData(): array
    {
        $start = now()->subMonths(11)->startOfMonth();
        $period = CarbonPeriod::create($start, '1 month', now()->startOfMonth());

        $labels = [];
        $candidateCounts = [];
        $hiredCounts = [];

        $registrations = Candidate::query()
            ->where('created_at', '>=', $start)
            ->get()
            ->groupBy(fn (Candidate $candidate) => $candidate->created_at->format('Y-m'))
            ->map->count();

        $hiredApplies = Apply::query()
            ->where('status', 'HIRED')
            ->where('created_at', '>=', $start)
            ->get()
            ->groupBy(fn (Apply $apply) => $apply->created_at->format('Y-m'))
            ->map->count();

        foreach ($period as $month) {
            /** @var Carbon $month */
            $key = $month->format('Y-m');
            $labels[] = $month->translatedFormat('M Y');
            $candidateCounts[] = (int) ($registrations[$key] ?? 0);
            $hiredCounts[] = (int) ($hiredApplies[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'candidates' => $candidateCounts,
            'hired' => $hiredCounts,
        ];
    }
}
