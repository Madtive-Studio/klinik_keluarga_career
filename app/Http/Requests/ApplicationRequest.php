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
            'type_of_document' => 'required',  
            'cover_letter'     => 'required',
            'description'      => 'required',
        ];

        if (strtoupper($this->input('type_of_document')) === 'UPLOAD') {
            $rules['new_document'] = 'required|file|mimes:pdf,doc,docx|max:20480';
        } else {
            $rules['document_id'] = 'required'; 
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'type_of_document.required' => 'Pilih jenis dokumen terlebih dahulu.',
            'cover_letter.required'     => 'Cover letter wajib diisi.',
            'description.required'      => 'Deskripsi wajib diisi.',
            'new_document.required'     => 'File dokumen wajib diupload.',
            'new_document.mimes'        => 'Format file harus PDF, DOC, atau DOCX.',
            'new_document.max'          => 'Ukuran file maksimal 8MB.',
            'document_id.required'      => 'Pilih dokumen CV/Resume terlebih dahulu.',
        ];
    }
}