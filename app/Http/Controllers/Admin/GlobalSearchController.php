<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apply;
use App\Models\Batch;
use App\Models\Candidate;
use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = trim((string) $request->get('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $keyword = '%' . mb_strtolower($query) . '%';
        $results = [];

        Job::query()
            ->where(function ($builder) use ($keyword) {
                $builder->whereRaw('LOWER(title) LIKE ?', [$keyword])
                    ->orWhereRaw('LOWER(code) LIKE ?', [$keyword]);
            })
            ->limit(5)
            ->get(['id', 'title', 'code'])
            ->each(function (Job $job) use (&$results) {
                $results[] = [
                    'type' => __('admin.search.job'),
                    'label' => $job->code . ' - ' . $job->title,
                    'url' => route('admin.jobs.edit', $job->id),
                ];
            });

        Candidate::query()
            ->where(function ($builder) use ($keyword) {
                $builder->whereRaw('LOWER(name) LIKE ?', [$keyword])
                    ->orWhereRaw('LOWER(email) LIKE ?', [$keyword]);
            })
            ->limit(5)
            ->get(['id', 'name', 'email'])
            ->each(function (Candidate $candidate) use (&$results) {
                $results[] = [
                    'type' => __('admin.search.candidate'),
                    'label' => $candidate->name . ' (' . $candidate->email . ')',
                    'url' => route('admin.candidates.index') . '?q=' . urlencode($candidate->email),
                ];
            });

        Apply::query()
            ->with(['candidate', 'job'])
            ->whereHas('candidate', function ($builder) use ($keyword) {
                $builder->whereRaw('LOWER(name) LIKE ?', [$keyword])
                    ->orWhereRaw('LOWER(email) LIKE ?', [$keyword]);
            })
            ->limit(5)
            ->get()
            ->each(function (Apply $apply) use (&$results) {
                $results[] = [
                    'type' => __('admin.search.apply'),
                    'label' => ($apply->candidate->name ?? '-') . ' → ' . ($apply->job->title ?? '-'),
                    'url' => route('admin.applies.show', $apply->id),
                ];
            });

        Batch::query()
            ->where(function ($builder) use ($keyword) {
                $builder->whereRaw('LOWER(name) LIKE ?', [$keyword])
                    ->orWhereRaw('LOWER(code) LIKE ?', [$keyword]);
            })
            ->limit(5)
            ->get(['id', 'name', 'code'])
            ->each(function (Batch $batch) use (&$results) {
                $results[] = [
                    'type' => __('admin.search.batch'),
                    'label' => $batch->code . ' - ' . $batch->name,
                    'url' => route('admin.batches.index') . '?q=' . urlencode($batch->code),
                ];
            });

        Category::query()
            ->whereRaw('LOWER(name) LIKE ?', [$keyword])
            ->limit(5)
            ->get(['id', 'name'])
            ->each(function (Category $category) use (&$results) {
                $results[] = [
                    'type' => __('admin.search.category'),
                    'label' => $category->name,
                    'url' => route('admin.categories.index') . '?q=' . urlencode($category->name),
                ];
            });

        return response()->json(['results' => $results]);
    }
}
