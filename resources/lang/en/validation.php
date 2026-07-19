<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'job_uuid' => [
            'required' => 'Invalid job vacancy.',
            'exists' => 'Job vacancy not found.',
        ],
        'type_of_document' => [
            'required' => 'Please select a document type first.',
        ],
        'cover_letter' => [
            'required' => 'Cover letter is required.',
        ],
        'description' => [
            'required' => 'Description is required.',
        ],
        'new_document' => [
            'required' => 'Document file is required.',
            'file' => 'CV file must be a valid file.',
            'uploaded' => 'File failed to upload. Maximum size is 20MB. Please try again.',
            'mimes' => 'File format must be PDF, DOC, or DOCX.',
            'max' => 'Maximum file size is 20MB.',
        ],
        'document_id' => [
            'required' => 'Please select a CV/Resume document first.',
        ],
        'file' => [
            'required' => 'File is required.',
            'file' => 'File must be a valid file.',
            'mimes' => 'File must be an image, PDF, Word, or Excel file.',
            'max' => 'File size must be less than 20MB.',
        ],
        'image' => [
            'image' => 'Image must be a valid image file.',
            'mimes' => 'Image format must be JPG, JPEG, PNG, GIF, or WEBP.',
            'max' => 'Maximum image size is 5MB.',
            'uploaded' => 'Image failed to upload. Maximum size is 5MB.',
        ],
        'images' => [
            'max' => 'Maximum 3 job images allowed.',
            'invalid_path' => 'Invalid image path.',
            'missing_file' => 'Image file not found.',
        ],
        'type' => [
            'required' => 'Type is required.',
            'string' => 'Type must be text.',
        ],
        'weight_education' => [
            'weight_total' => 'Total scoring weight must be 100. Current total is :total.',
            'threshold_order' => 'Recommended score threshold must be greater than review score threshold.',
        ],
        'quota' => [
            'exceeds_batch' => 'Job quota exceeds remaining batch quota. Batch quota: :batch_quota, allocated: :allocated, remaining: :remaining.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'batch_id' => 'batch',
        'category_id' => 'category',
        'title' => 'job title',
        'type' => 'job type',
        'quota' => 'quota',
        'salary' => 'salary',
        'salary_min' => 'minimum salary',
        'salary_max' => 'maximum salary',
        'experience' => 'experience',
        'qualification' => 'qualification',
        'description' => 'description',
        'min_education' => 'minimum education',
        'required_skills' => 'required skills',
        'weight_education' => 'education weight',
        'weight_experience' => 'experience weight',
        'weight_skills' => 'skills weight',
        'weight_profile' => 'profile completeness weight',
        'weight_cover_letter' => 'cover letter weight',
        'threshold_shortlist' => 'recommended score threshold',
        'threshold_reject' => 'review score threshold',
        'job_uuid' => 'job vacancy',
        'type_of_document' => 'document type',
        'cover_letter' => 'cover letter',
        'new_document' => 'CV file',
        'document_id' => 'CV document',
        'file' => 'file',
        'image' => 'job image',
        'images' => 'job images',
        'education_level' => 'education level',
        'years_of_experience' => 'years of experience',
        'skills.*.name' => 'skill name',
        'name' => 'name',
        'email' => 'email',
        'current_password' => 'current password',
        'password' => 'new password',
    ],

];
