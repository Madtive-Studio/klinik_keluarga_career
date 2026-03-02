<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.candidates.index', [

        ]);
    }

    public function datatables()
    {
        $query = Candidate::with(['CVs'])->orderBy('created_at', 'DESC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                if (!isset($row->name) && empty($row->name)) {
                    return '-';
                }
                return $row->name;
            })
            ->addColumn('cv_total', function ($row) {
                return $row->CVs->count() . " Total CV/Resume";
            })
            ->addColumn('is_online', function ($row) {
                $status = '<div class="d-flex align-items-center lh-1 me-4 mb-4 mb-sm-0">';
                $status .= '<span class="badge badge-dot bg-success me-1"></span> Online';
                $status .= '</div>';

                return $status;
            })
            ->rawColumns(['is_online', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
