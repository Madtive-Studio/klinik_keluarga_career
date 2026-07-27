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
            'documents' => 'required|array|min:1|max:10',
            'documents.*.file' => 'required|file|mimes:pdf,doc,docx|max:20480',
            'documents.*.type' => ['required', 'string', Rule::enum(DocumentType::class)],
            'cover_letter' => 'required',
            'description' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'documents' => __('validation.attributes.documents'),
            'documents.*.file' => __('validation.attributes.document_file'),
            'documents.*.type' => __('validation.attributes.document_type'),
            'job_uuid' => __('validation.attributes.job_uuid'),
            'cover_letter' => __('validation.attributes.cover_letter'),
            'description' => __('validation.attributes.description'),
        ];
    }

    public function messages(): array
    {
        return [
            'documents.required' => __('validation.documents_required'),
            'documents.min' => __('validation.documents_min', ['min' => 1]),
            'documents.max' => __('validation.documents_max', ['max' => 10]),
            'documents.*.file.required' => __('validation.document_file_required'),
            'documents.*.file.mimes' => __('validation.document_file_mimes'),
            'documents.*.type.required' => __('validation.document_type_required'),
            'documents.*.type.Illuminate\Validation\Rules\Enum' => __('validation.document_type_invalid'),
        ];
    }
}
