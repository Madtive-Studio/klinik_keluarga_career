<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Form memakai value="upload" / "select" (lihat apply.blade.php).
     * Harus selaras dengan ApplicationService::submitApplication().
     */
    public function rules(): array
    {
        $rules = [
            'job_uuid'         => 'required|exists:jobs,uuid',
            'type_of_document' => 'required',
            'cover_letter'     => 'required',
            'description'      => 'required',
        ];

        $type = strtolower((string) $this->input('type_of_document'));

        if ($type === 'upload') {
            // mimes: gagal upload PHP (ukuran server, dll.) memicu rule "uploaded", bukan "mimes"
            $rules['new_document'] = 'required|file|mimes:pdf,doc,docx|max:20480';
        } else {
            $rules['document_id'] = 'required';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'new_document' => 'berkas CV',
            'document_id'  => 'dokumen CV',
        ];
    }

    public function messages(): array
    {
        return [
            'job_uuid.required'         => 'Lowongan tidak valid.',
            'job_uuid.exists'           => 'Lowongan pekerjaan tidak ditemukan.',
            'type_of_document.required' => 'Pilih jenis dokumen terlebih dahulu.',
            'cover_letter.required'     => 'Cover letter wajib diisi.',
            'description.required'      => 'Deskripsi wajib diisi.',
            'new_document.required'     => 'File dokumen wajib diupload.',
            'new_document.file'       => 'Berkas CV harus berupa file yang valid.',
            'new_document.uploaded'   => 'File tidak berhasil diunggah ke server. Pastikan ukuran maksimal 20MB dan coba lagi.',
            'new_document.mimes'        => 'Format file harus PDF, DOC, atau DOCX.',
            'new_document.max'          => 'Ukuran file maksimal 20MB.',
            'document_id.required'      => 'Pilih dokumen CV/Resume terlebih dahulu.',
        ];
    }
}