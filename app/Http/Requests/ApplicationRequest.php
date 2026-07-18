<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'job_uuid' => 'required|exists:jobs,uuid',
            'type_of_document' => 'required',
            'cover_letter' => 'required',
            'description' => 'required',
        ];

        $type = strtolower((string) $this->input('type_of_document'));

        if ($type === 'upload') {
            $rules['new_document'] = 'required|file|mimes:pdf,doc,docx|max:20480';
        } else {
            $rules['document_id'] = 'required';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'new_document' => __('validation.attributes.new_document'),
            'document_id' => __('validation.attributes.document_id'),
            'job_uuid' => __('validation.attributes.job_uuid'),
            'type_of_document' => __('validation.attributes.type_of_document'),
            'cover_letter' => __('validation.attributes.cover_letter'),
            'description' => __('validation.attributes.description'),
        ];
    }
}
