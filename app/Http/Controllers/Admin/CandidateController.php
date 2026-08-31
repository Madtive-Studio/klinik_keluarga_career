<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CandidateController extends Controller
{
    public function index()
    {
        return view('admin.candidates.index');
    }

    public function datatables(Request $request)
    {
        $query = Candidate::withCount('documents')->orderBy('id', 'ASC');

        if ($request->filled('q')) {
            $keyword = '%' . mb_strtolower($request->get('q')) . '%';
            $query->where(function ($builder) use ($keyword) {
                $builder->whereRaw('LOWER(name) LIKE ?', [$keyword])
                    ->orWhereRaw('LOWER(email) LIKE ?', [$keyword])
                    ->orWhereRaw('LOWER(phone) LIKE ?', [$keyword]);
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('email_verified_at', function ($row) {
                return $row->email_verified_at
                    ? Carbon::parse($row->email_verified_at)->diffForHumans()
                    : '-';
            })
            ->editColumn('birth_date', function ($row) {
                return $row->birth_date
                    ? Carbon::parse($row->birth_date)->translatedFormat('d M Y')
                    : '-';
            })
            ->addColumn('cv_total', function ($row) {
                return $row->documents_count . ' Total CV/Resume';
            })
            ->addColumn('is_online', function ($row) {
                return $row->email_verified_at
                    ? '<span class="badge bg-label-success">Verified</span>'
                    : '<span class="badge bg-label-secondary">Unverified</span>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group">';
                $btn .= '<a href="' . route('admin.candidates.show', $row->id) . '" class="btn btn-sm btn-primary detail" title="' . __('admin.applies.preview') . '"><i class="ti ti-eye"></i></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['is_online', 'action'])
            ->make(true);
    }

    public function show($id)
    {
        $candidate = Candidate::with([
            'profile',
            'skills',
            'documents',
            'applies.job.category',
            'applies.batch'
        ])->findOrFail($id);

        return view('admin.candidates.detail', compact('candidate'));
    }
}
