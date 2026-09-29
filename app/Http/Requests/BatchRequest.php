<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('code')) {
            $this->merge(['code' => trim((string) $this->input('code'))]);
        }

        if ($this->has('quota')) {
            $rawQuota = trim((string) $this->input('quota'));

            // Check if quota consists strictly of digits (positive integer)
            if ($rawQuota !== '' && preg_match('/^\d+$/', $rawQuota)) {
                $maxInt = '2147483647'; // Standard 32-bit signed integer limit
                $cleanQuota = ltrim($rawQuota, '0') ?: '0';

                // If the number exceeds the maximum integer limit, normalize to 2147483647
                if (strlen($cleanQuota) > strlen($maxInt) || (strlen($cleanQuota) === strlen($maxInt) && strcmp($cleanQuota, $maxInt) > 0)) {
                    $this->merge(['quota' => 2147483647]);
                } else {
                    $this->merge(['quota' => (int) $cleanQuota]);
                }
            }
            // If rawQuota contains non-numeric characters (e.g. 'abc', '10a', '@#!'),
            // do not normalize so that the 'integer' rule will fail and return a validation error.
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'start_date' => ['required', 'date_format:d-m-Y H:i:s'],
            'end_date' => ['required', 'date_format:d-m-Y H:i:s'],
        ];
    }

    /**
     * Custom validation attributes.
     */
    public function attributes(): array
    {
        return [
            'code' => __('admin.batches.code'),
            'name' => __('admin.batches.name'),
            'quota' => __('admin.batches.quota'),
            'start_date' => __('admin.batches.start_date'),
            'end_date' => __('admin.batches.end_date'),
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'quota.integer' => __('validation.integer', ['attribute' => __('admin.batches.quota')]),
            'end_date.after' => 'End date tidak boleh sebelum atau sama dengan start date.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $startDate = $this->input('start_date');
            $endDate = $this->input('end_date');

            if ($startDate && $endDate) {
                try {
                    $start = Carbon::createFromFormat('d-m-Y H:i:s', $startDate);
                    $end = Carbon::createFromFormat('d-m-Y H:i:s', $endDate);

                    if ($end->lte($start)) {
                        $validator->errors()->add('end_date', 'End date tidak boleh sebelum atau sama dengan start date.');
                    }
                } catch (\Exception $e) {
                    // Handled by date_format validation rule
                }
            }
        });
    }
}
