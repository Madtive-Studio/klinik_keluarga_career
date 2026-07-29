<?php

namespace App\Http\Requests;

use App\Enums\DocumentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_uuid' => 'required|exists:jobs,uuid',
            'existing_documents' => 'nullable|array',
            'existing_documents.*' => 'integer|exists:documents,id',
            'cover_letter' => 'required',
            'description' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'existing_documents' => __('validation.attributes.existing_documents'),
            'existing_documents.*' => __('validation.attributes.existing_documents'),
            'job_uuid' => __('validation.attributes.job_uuid'),
            'cover_letter' => __('validation.attributes.cover_letter'),
            'description' => __('validation.attributes.description'),
        ];
    }

    public function messages(): array
    {
        return [
            'existing_documents.*.exists' => __('validation.existing_documents_invalid'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $documentIds = $this->input('existing_documents', []);
            if (empty($documentIds)) {
                $validator->errors()->add('existing_documents', __('validation.existing_documents_required'));
                return;
            }
            $candidate = auth('candidate')->user();
            $validCount = $candidate->documents()->whereIn('id', $documentIds)->count();
            if ($validCount !== count($documentIds)) {
                $validator->errors()->add('existing_documents', __('validation.existing_documents_invalid'));
            }
        });
    }
}
