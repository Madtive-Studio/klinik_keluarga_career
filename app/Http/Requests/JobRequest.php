<?php

namespace App\Http\Requests;

use App\Enums\EducationLevel;
use App\Services\JobImageService;
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
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string'],
            'quota' => ['required', 'numeric', 'min:1'],
            'salary_min' => ['required', 'integer', 'min:1'],
            'salary_max' => ['required', 'integer', 'min:1', 'gte:salary_min'],
            'experience' => ['required', 'string', 'max:255'],
            'qualification' => ['required', 'string'],
            'description' => ['required', 'string'],
            'min_education' => ['nullable', Rule::in(EducationLevel::values())],
            'required_skills' => ['nullable', 'string'],
            'weight_education' => ['nullable', 'integer', 'min:0', 'max:100'],
            'weight_experience' => ['nullable', 'integer', 'min:0', 'max:100'],
            'weight_skills' => ['nullable', 'integer', 'min:0', 'max:100'],
            'weight_profile' => ['nullable', 'integer', 'min:0', 'max:100'],
            'weight_cover_letter' => ['nullable', 'integer', 'min:0', 'max:100'],
            'threshold_shortlist' => ['nullable', 'integer', 'min:0', 'max:100'],
            'threshold_reject' => ['nullable', 'integer', 'min:0', 'max:100'],
            'images' => ['nullable', 'array', 'max:' . JobImageService::MAX_IMAGES],
            'images.*' => ['string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'batch_id' => __('validation.attributes.batch_id'),
            'category_id' => __('validation.attributes.category_id'),
            'title' => __('validation.attributes.title'),
            'type' => __('validation.attributes.type'),
            'quota' => __('validation.attributes.quota'),
            'salary_min' => __('validation.attributes.salary_min'),
            'salary_max' => __('validation.attributes.salary_max'),
            'experience' => __('validation.attributes.experience'),
            'qualification' => __('validation.attributes.qualification'),
            'description' => __('validation.attributes.description'),
            'min_education' => __('validation.attributes.min_education'),
            'required_skills' => __('validation.attributes.required_skills'),
            'weight_education' => __('validation.attributes.weight_education'),
            'weight_experience' => __('validation.attributes.weight_experience'),
            'weight_skills' => __('validation.attributes.weight_skills'),
            'weight_profile' => __('validation.attributes.weight_profile'),
            'weight_cover_letter' => __('validation.attributes.weight_cover_letter'),
            'threshold_shortlist' => __('validation.attributes.threshold_shortlist'),
            'threshold_reject' => __('validation.attributes.threshold_reject'),
            'images' => __('validation.attributes.images'),
            'images.*' => __('validation.attributes.image'),
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
                $validator->errors()->add(
                    'weight_education',
                    __('validation.custom.weight_education.weight_total', ['total' => array_sum($weights)])
                );
            }

            $shortlist = (int) $this->input('threshold_shortlist', 70);
            $reject = (int) $this->input('threshold_reject', 40);

            if ($shortlist <= $reject) {
                $validator->errors()->add(
                    'threshold_shortlist',
                    __('validation.custom.weight_education.threshold_order')
                );
            }

            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $imageService = app(JobImageService::class);
            $paths = $imageService->normalizePaths($this->input('images'));

            try {
                $imageService->assertPathsBelongToJob($paths, (string) $this->input('uuid'));
            } catch (\InvalidArgumentException $exception) {
                $validator->errors()->add('images', $exception->getMessage());
            }
        });
    }

    public function resolvedImagePaths(): array
    {
        return app(JobImageService::class)->normalizePaths($this->input('images'));
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
            'min_experience_years' => parseJobExperienceYears($this->input('experience')),
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
