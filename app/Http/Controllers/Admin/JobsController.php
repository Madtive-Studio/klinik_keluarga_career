<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Batch;
use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.jobs.index', [

        ]);
    }

    public function datatables()
    {
        $query = Job::with(['batch', 'category'])->orderBy('created_at', 'DESC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('is_show_salary', function ($row) {
                return $row->is_show_salary ? 'YES' : 'NO';
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group" aria-label="Basic example">';
                $btn .= '<button type="button" class="btn btn-sm btn-warning edit" data-route="'.route('admin.jobs.edit', $row->id).'"><i class="ti ti-pencil"></i></button>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete" data-route="'.route('admin.jobs.destroy', $row->id).'"><i class="ti ti-trash"></i></button>';
                $btn .= '</div>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $code = '#'.strtoupper(substr(uniqid(), 0, 10));
        $batches = Batch::orderBy('created_at', 'DESC')->get();
        $categories = Category::orderBy('created_at', 'DESC')->get();

        return view('admin.jobs.form', [
            'uuid' => (string)Str::uuid(),
            'code' => $code,
            'batches' => $batches,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request['user_id'] = auth()->user()->id;
        $request['is_show_salary'] = strtolower($request['is_show_salary']) == 'on' ? true : false;
        Job::create($request->all());
        return redirect()->route('admin.jobs.index')->with('success', 'Berhasil membuat lowongan pekerjaan baru');
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
        $job = Job::findOrFail($id);
        $batches = Batch::orderBy('created_at', 'DESC')->get();
        $categories = Category::orderBy('created_at', 'DESC')->get();

        return view('admin.jobs.form', [
            'job' => $job,
            'batches' => $batches,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request['user_id'] = auth()->user()->id;
        $request['is_show_salary'] = strtolower($request['is_show_salary']) == 'on' ? true : false;
        
        Job::findOrFail($id)->update($request->all());
        return redirect()->route('admin.jobs.index')->with('success', 'Berhasil mengubah data lowongan pekerjaan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job = Job::findOrFail($id);
        if ($job) {
            $job->delete();
        }
        return redirect()->route('admin.jobs.index')->with('success', 'Berhasil menghapus data lowongan pekerjaan');
    }
}
