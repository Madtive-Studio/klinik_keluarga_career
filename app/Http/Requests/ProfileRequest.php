<?php

namespace App\Http\Requests;

use App\Enums\EducationLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('candidate')->check();
    }

    public function rules(): array
    {
        $candidateId = auth('candidate')->id();

        return [
            'name' => ['required', 'string', 'max:150'],
            'username' => [
                'required',
                'string',
                'alpha_dash',
                'max:50',
                Rule::unique('candidates', 'username')->ignore($candidateId),
            ],
            'phone' => ['required', 'numeric', 'digits_between:9,15'],
            'education_level' => ['required', Rule::in(EducationLevel::values())],
            'major' => ['nullable', 'string', 'max:255'],
            'university' => ['nullable', 'string', 'max:255'],
            'gpa' => ['nullable', 'numeric', 'min:0', 'max:4'],
            'years_of_experience' => ['required', 'integer', 'min:0', 'max:50'],
            'last_position' => ['nullable', 'string', 'max:255'],
            'last_company' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'expected_salary' => ['nullable', 'integer', 'min:0'],
            'skills' => ['nullable'],
            'skills.*' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function attributes(): array
    {
        return [
            'education_level' => __('validation.attributes.education_level'),
            'years_of_experience' => __('validation.attributes.years_of_experience'),
        ];
    }
}
