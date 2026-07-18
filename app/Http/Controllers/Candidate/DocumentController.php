<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Repositories\DocumentRepository;
use App\Enums\DocumentType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function __construct(
        protected DocumentRepository $repository
    ) {}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $userId = Auth::guard('candidate')->id();
        $perPage = (int) request('per_page', 5);
        $requestedType = request('type');
        $typeFilter = '*';
        $activeType = null;

        if ($requestedType) {
            $activeType = DocumentType::tryFrom($requestedType);

            if (!$activeType) {
                abort(404);
            }

            $typeFilter = $activeType->value;
        }

        $candidate = $this->repository->getCandidateDocumentsPaginated($userId, $perPage, $typeFilter);

        return view('candidate.documents.index', [
            'candidate' => $candidate,
            'activeType' => $activeType,
            'documentTypes' => DocumentType::getWithLabels(),
        ]);
    }

    /**
     * Redirect legacy upload page to documents index.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('candidate.my.documents.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocumentRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $this->repository->store($request);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('messages.document.upload_success'),
                ]);
            }

            return redirect()->back()->with('success', __('messages.document.upload_success'));
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('messages.document.upload_failed', ['error' => $e->getMessage()]),
                ], 422);
            }

            return redirect()->back()->with('error', __('messages.document.upload_failed', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $success = $this->repository->delete($id);
            $message = $success
                ? __('messages.document.delete_success', ['message' => __('messages.document.deleted')])
                : __('messages.document.delete_failed_generic');
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.document.delete_failed', ['error' => $e->getMessage()]));
        }
    }
}
