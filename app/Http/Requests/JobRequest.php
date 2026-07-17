<?php

namespace App\Http\Requests;

use App\Enums\EducationLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class JobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'string'],
            'code' => ['required', 'string'],
            'batch_id' => ['required', 'exists:batches,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string'],
            'type' => ['required', 'string'],
            'quota' => ['required', 'numeric'],
            'salary' => ['required', 'string'],
            'experience' => ['required', 'string'],
            'qualification' => ['required', 'string'],
            'description' => ['required', 'string'],
            'min_education' => ['nullable', Rule::in(EducationLevel::values())],
            'min_experience_years' => ['nullable', 'integer', 'min:0', 'max:50'],
            'required_skills' => ['nullable', 'string'],
            'weight_education' => ['nullable', 'integer', 'min:0', 'max:100'],
            'weight_experience' => ['nullable', 'integer', 'min:0', 'max:100'],
            'weight_skills' => ['nullable', 'integer', 'min:0', 'max:100'],
            'weight_profile' => ['nullable', 'integer', 'min:0', 'max:100'],
            'weight_cover_letter' => ['nullable', 'integer', 'min:0', 'max:100'],
            'threshold_shortlist' => ['nullable', 'integer', 'min:0', 'max:100'],
            'threshold_reject' => ['nullable', 'integer', 'min:0', 'max:100'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $weights = [
                (int) $this->input('weight_education', 25),
                (int) $this->input('weight_experience', 25),
                (int) $this->input('weight_skills', 30),
                (int) $this->input('weight_profile', 10),
                (int) $this->input('weight_cover_letter', 10),
            ];

            if (array_sum($weights) !== 100) {
                $validator->errors()->add('weight_education', 'Total bobot penilaian harus 100.');
            }

            $shortlist = (int) $this->input('threshold_shortlist', 70);
            $reject = (int) $this->input('threshold_reject', 40);

            if ($shortlist <= $reject) {
                $validator->errors()->add('threshold_shortlist', 'Threshold shortlist harus lebih besar dari threshold reject.');
            }
        });
    }

    public function criteriaAttributes(): array
    {
        $skills = collect(preg_split('/[,;\n]+/', (string) $this->input('required_skills', '')))
            ->map(fn (string $skill) => trim($skill))
            ->filter()
            ->values()
            ->all();

        return [
            'min_education' => $this->input('min_education') ?: null,
            'min_experience_years' => (int) $this->input('min_experience_years', 0),
            'required_skills' => $skills,
            'weight_education' => (int) $this->input('weight_education', 25),
            'weight_experience' => (int) $this->input('weight_experience', 25),
            'weight_skills' => (int) $this->input('weight_skills', 30),
            'weight_profile' => (int) $this->input('weight_profile', 10),
            'weight_cover_letter' => (int) $this->input('weight_cover_letter', 10),
            'threshold_shortlist' => (int) $this->input('threshold_shortlist', 70),
            'threshold_reject' => (int) $this->input('threshold_reject', 40),
        ];
    }
}
