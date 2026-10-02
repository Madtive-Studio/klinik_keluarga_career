<?php

namespace App\Http\Requests;

use App\Enums\EducationLevel;
use App\Models\Batch;
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

    protected function prepareForValidation(): void
    {
        if ($this->has('code')) {
            $this->merge(['code' => trim((string) $this->input('code'))]);
        }
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'string'],
            'code' => ['required', 'string', 'max:50'],
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
            'images' => ['nullable', 'array', 'max:' . JobImageService::MAX_IMAGES],
            'images.*' => ['string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => __('admin.jobs.code'),
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
            'images' => __('validation.attributes.images'),
            'images.*' => __('validation.attributes.image'),
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $batchId = (int) $this->input('batch_id');
            $requestedQuota = (int) $this->input('quota');
            $batch = Batch::find($batchId);

            if ($batch) {
                $excludeJobId = null;

                if ($this->isMethod('put') || $this->isMethod('patch')) {
                    $excludeJobId = (int) $this->route('job');
                }

                $allocatedQuota = $batch->allocatedQuota($excludeJobId);

                if (($allocatedQuota + $requestedQuota) > (int) $batch->quota) {
                    $validator->errors()->add(
                        'quota',
                        __('validation.custom.quota.exceeds_batch', [
                            'batch_quota' => (int) $batch->quota,
                            'allocated' => $allocatedQuota,
                            'remaining' => $batch->remainingQuota($excludeJobId),
                        ])
                    );
                }
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
}
