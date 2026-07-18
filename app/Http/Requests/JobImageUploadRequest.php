<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobImageUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'job_uuid' => ['required', 'uuid'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function attributes(): array
    {
        return [
            'job_uuid' => __('validation.attributes.job_uuid'),
            'image' => __('validation.attributes.image'),
        ];
    }
}
