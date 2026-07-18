<?php

namespace App\Http\Requests;

use App\Enums\EducationLevel;
use App\Enums\SkillLevel;
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
        return [
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
            'availability_date' => ['nullable', 'date'],
            'skills' => ['nullable', 'array'],
            'skills.*.name' => ['required_with:skills', 'string', 'max:255'],
            'skills.*.level' => ['nullable', Rule::in(SkillLevel::values())],
        ];
    }

    public function attributes(): array
    {
        return [
            'education_level' => __('validation.attributes.education_level'),
            'years_of_experience' => __('validation.attributes.years_of_experience'),
            'skills.*.name' => __('validation.attributes.skills.*.name'),
        ];
    }
}
