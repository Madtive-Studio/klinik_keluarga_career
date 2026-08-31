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
            ->editColumn('phone', function ($row) {
                return $row->phone ?? '-';
            })
            ->editColumn('address', function ($row) {
                return $row->address ?? '-';
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group">';
                $btn .= '<a href="' . route('admin.candidates.show', $row->id) . '" class="btn btn-sm btn-primary detail" title="' . __('admin.applies.preview') . '"><i class="ti ti-eye"></i></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action'])
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
