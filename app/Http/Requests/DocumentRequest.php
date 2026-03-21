<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg,webp,xls,xlsx|max:20480',
            'type' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'File wajib diisi.',
            'file.file' => 'File harus berupa file.',
            'file.mimes' => 'File harus berupa Gambar, PDF, Word, atau Excel.',
            'file.max' => 'Ukuran file harus kurang dari 5MB.',
            'type.required' => 'Tipe wajib diisi.',
            'type.string' => 'Tipe harus berupa string.',
        ];
    }
}
