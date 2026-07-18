<?php

namespace App\Http\Requests;

use App\Enums\DocumentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg,webp,gif,xls,xlsx|max:20480',
            'type' => ['required', 'string', Rule::enum(DocumentType::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'file' => __('validation.attributes.file'),
            'type' => __('validation.attributes.type'),
        ];
    }
}
