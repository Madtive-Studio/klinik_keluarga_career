<?php

namespace App\Http\Requests;

use App\Enums\ApplicationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicantStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(ApplicationStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status lamaran wajib dipilih.',
            'status.enum' => 'Status lamaran tidak valid.',
        ];
    }
}
