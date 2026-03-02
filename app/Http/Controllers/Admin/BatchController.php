<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $code = '#'.strtoupper(substr(uniqid(), 0, 10));
        return view('admin.batches.index', [
            'code' => $code
        ]);
    }

    public function datatables()
    {
        $query = Batch::orderBy('created_at', 'DESC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('status', function ($row) {
                $bg = $row->status == 'ACTIVE' ? 'success' : 'danger';
                $badge = '<span class="badge bg-'.$bg.'">'.strtoupper($row->status).'</span>';
                return $badge;
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group" aria-label="Basic example">';
                $btn .= '<button type="button" class="btn btn-sm btn-success update" data-route="'.route('admin.batches.status', $row->id).'"><i class="ti ti-power"></i></button>';
                $btn .= '<button type="button" class="btn btn-sm btn-warning edit" data-id="'.$row->id.'" data-code="'.$row->code.'"
                    data-name="'.$row->name.'" data-start_date="'.$row->start_date.'" data-end_date="'.$row->end_date.'" data-quota="'.$row->quota.'"
                    data-status="'.$row->status.'" data-route="'.route('admin.batches.edit', $row->id).'"><i class="ti ti-pencil"></i></button>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete" data-route="'.route('admin.batches.destroy', $row->id).'"><i class="ti ti-trash"></i></button>';
                $btn .= '</div>';

                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $code = '#'.strtoupper(substr(uniqid(), 0, 10));
        return view('admin.batches.form', [
            'code' => $code,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request['status'] = 'INACTIVE';
        Batch::create($request->all());
        return redirect()->route('admin.batches.index')->with('success', 'Berhasil membuat batch baru');
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
        $batch = Batch::findOrFail($id);
        return view('admin.batches.form', [
            'batch' => $batch,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Batch::findOrFail($id)->update($request->all());
        return redirect()->route('admin.batches.index')->with('success', 'Berhasil mengubah data batch');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $batch = Batch::findOrFail($id);
        if ($batch) {
            $batch->delete();
        }
        return redirect()->route('admin.batches.index')->with('success', 'Berhasil menghapus data batch');
    }

    public function status(string $id)
    {
        $batch = Batch::findOrFail($id);
        $batch->update(['status' => 'ACTIVE']);
        $other = Batch::whereNotIn('id', [$batch->id])->update(['status' => 'INACTIVE']);

        return redirect()->route('admin.batches.index')->with('success', 'Berhasil ubah status batch');
    }
}
