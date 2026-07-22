<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
        $query = Batch::orderBy('id', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('start_date', function ($row) {
                return Carbon::parse($row->start_date)->diffForHumans();
            })
            ->editColumn('end_date', function ($row) {
                return Carbon::parse($row->end_date)->diffForHumans();
            })
            ->editColumn('status', function ($row) {
                $isActive = $row->status === 'ACTIVE';
                $route = route('admin.batches.status', $row->id);
                $activeChecked = $isActive ? 'checked' : '';
                $inactiveChecked = $isActive ? '' : 'checked';
                return <<<HTML
                <div class="btn-group btn-group-sm" data-bs-toggle="buttons">
                    <input type="radio" class="btn-check batch-status-radio" name="batch_status_{$row->id}" id="batch_active_{$row->id}" value="ACTIVE" data-route="{$route}" {$activeChecked} autocomplete="off">
                    <label class="btn btn-outline-success {$activeChecked}" for="batch_active_{$row->id}">Aktif</label>
                    <input type="radio" class="btn-check batch-status-radio" name="batch_status_{$row->id}" id="batch_inactive_{$row->id}" value="INACTIVE" data-route="{$route}" {$inactiveChecked} autocomplete="off">
                    <label class="btn btn-outline-danger {$inactiveChecked}" for="batch_inactive_{$row->id}">Nonaktif</label>
                </div>
                HTML;
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group" aria-label="Basic example">';
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
        $data = $this->validatedBatchData($request);
        $data['status'] = 'INACTIVE';

        Batch::create($data);

        return redirect()->route('admin.batches.index')->with('success', __('messages.admin.batch.created'));
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
        Batch::findOrFail($id)->update($this->validatedBatchData($request));

        return redirect()->route('admin.batches.index')->with('success', __('messages.admin.batch.updated'));
    }

    private function validatedBatchData(Request $request): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'integer', 'min:0'],
            'start_date' => ['required', 'date_format:d-m-Y H:i:s'],
            'end_date' => ['required', 'date_format:d-m-Y H:i:s'],
        ]);

        $data['start_date'] = parseFlatpickrDatetime($data['start_date']);
        $data['end_date'] = parseFlatpickrDatetime($data['end_date']);

        if (Carbon::parse($data['end_date'])->lte(Carbon::parse($data['start_date']))) {
            throw ValidationException::withMessages([
                'end_date' => 'End date tidak boleh sebelum atau sama dengan start date.',
            ]);
        }

        return $data;
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
        return redirect()->route('admin.batches.index')->with('success', __('messages.admin.batch.deleted'));
    }

    public function status(Request $request, string $id)
    {
        $batch = Batch::findOrFail($id);
        $targetStatus = $request->get('status', 'ACTIVE');

        if ($targetStatus === 'ACTIVE') {
            $batch->update(['status' => 'ACTIVE']);
            Batch::whereNotIn('id', [$batch->id])->update(['status' => 'INACTIVE']);
        } else {
            $batch->update(['status' => 'INACTIVE']);
        }

        return response()->json(['success' => true]);
    }
}
