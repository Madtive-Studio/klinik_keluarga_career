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

    protected function prepareForValidation(): void
    {
        if ($this->has('existing_documents') && is_array($this->existing_documents)) {
            $filtered = array_values(array_unique(array_filter($this->existing_documents, function ($id) {
                return !is_null($id) && $id !== '';
            })));
            $this->merge([
                'existing_documents' => $filtered,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'job_uuid' => 'required|exists:jobs,uuid',
            'existing_documents' => 'required|array|min:1',
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
            'existing_documents.required' => __('validation.existing_documents_required'),
            'existing_documents.min' => __('validation.existing_documents_required'),
            'existing_documents.*.exists' => __('validation.existing_documents_invalid'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $documentIds = $this->input('existing_documents', []);
            if (!is_array($documentIds)) {
                $documentIds = [];
            }
            $documentIds = array_values(array_unique(array_filter($documentIds, function ($id) {
                return !is_null($id) && $id !== '';
            })));

            if (empty($documentIds)) {
                $validator->errors()->add('existing_documents', __('validation.existing_documents_required'));
                return;
            }

            $candidate = auth('candidate')->user();
            if (!$candidate) {
                return;
            }

            $validCount = $candidate->documents()->whereIn('id', $documentIds)->count();
            if ($validCount !== count($documentIds)) {
                $validator->errors()->add('existing_documents', __('validation.existing_documents_invalid'));
            }
        });
    }
}
