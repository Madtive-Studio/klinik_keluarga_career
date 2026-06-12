<?php

namespace App\Http\Controllers\Candidate;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use App\Enums\DocumentType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(): View
    {
        $documents = Document::where('candidate_id', Auth::guard('candidate')->id())
            ->orderByDesc('created_at')
            ->paginate((int) request('per_page', 5));

        return view('candidate.documents.index', [
            'candidate' => (object) [
                'documents_count' => $documents->total(),
                'documents' => $documents,
            ],
        ]);
    }

    public function create(): View
    {
        return view('candidate.documents.create', [
            'types' => DocumentType::getWithLabels(),
        ]);
    }

    public function store(DocumentRequest $request): RedirectResponse
    {
        try {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $fileName, 'public');

            $documentType = DocumentType::from($request->type);

            Document::create([
                'candidate_id' => Auth::guard('candidate')->id(),
                'name' => $file->getClientOriginalName(),
                'file' => $path,
                'type' => $documentType,
                'category' => $documentType->getCategory(),
                'status' => DocumentStatus::PENDING,
            ]);

            return redirect()->back()->with('success', 'Berhasil upload dokumen');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal upload: ' . $e->getMessage());
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $document = Document::where('candidate_id', Auth::guard('candidate')->id())
                ->findOrFail($id);

            $document->delete();

            return redirect()->back()->with('success', 'Dokumen berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
