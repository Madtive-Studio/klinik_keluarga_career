<?php

return [
    'auth' => [
        'email_not_verified' => 'Your email has not been verified yet',
        'invalid_credentials' => 'Invalid email or password!',
        'register_success' => 'Registration successful, please check your email for verification',
        'email_verified' => 'Your email has been verified',
    ],
    'profile' => [
        'saved' => 'Profile saved successfully.',
    ],
    'document' => [
        'upload_success' => 'Document uploaded successfully',
        'upload_failed' => 'Upload failed: :error',
        'deleted' => 'Document deleted successfully',
        'delete_success' => ':message',
        'delete_failed' => 'Delete failed: :error',
        'delete_failed_generic' => 'Failed to delete document',
    ],
    'application' => [
        'already_applied_html' => 'You have already applied for this job. Please check <a href=":url"><u>My Applications</u></a> to view your application status.',
        'already_applied' => 'You have already applied for this job',
        'not_found' => 'Application data not found.',
    ],
    'admin' => [
        'auth' => [
            'invalid_credentials' => 'Invalid email or password!',
        ],
        'profile' => [
            'updated' => 'Profile updated successfully.',
        ],
        'batch' => [
            'created' => 'Batch created successfully',
            'updated' => 'Batch updated successfully',
            'deleted' => 'Batch deleted successfully',
            'status_updated' => 'Batch status updated successfully',
        ],
        'category' => [
            'created' => 'Category created successfully',
            'updated' => 'Category updated successfully',
            'deleted' => 'Category deleted successfully',
        ],
        'job' => [
            'created' => 'Job vacancy created successfully',
            'updated' => 'Job vacancy updated successfully',
            'deleted' => 'Job vacancy deleted successfully',
        ],
        'apply' => [
            'status_updated' => 'Application status updated successfully',
        ],
        'schedule_interview' => [
            'invalid_apply' => 'Invalid application data',
            'created' => 'Interview schedule created successfully',
            'resent' => 'Interview invitation resent successfully',
            'updated' => 'Interview schedule updated successfully',
            'deleted' => 'Interview schedule deleted successfully',
        ],
    ],
];
