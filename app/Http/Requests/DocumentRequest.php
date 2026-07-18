<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg,webp,xls,xlsx|max:20480',
            'type' => 'required|string',
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
