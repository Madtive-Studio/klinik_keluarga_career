<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobCriteria extends Model
{
    use HasFactory;

    protected $table = 'job_criteria';

    protected $fillable = [
        'job_id',
        'min_education',
        'min_experience_years',
        'required_skills',
        'weight_education',
        'weight_experience',
        'weight_skills',
        'weight_profile',
        'weight_cover_letter',
        'threshold_shortlist',
        'threshold_reject',
    ];

    protected $casts = [
        'required_skills' => 'array',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public static function defaultsForJob(int $jobId): array
    {
        return [
            'job_id' => $jobId,
            'min_education' => null,
            'min_experience_years' => 0,
            'required_skills' => [],
            'weight_education' => 25,
            'weight_experience' => 25,
            'weight_skills' => 30,
            'weight_profile' => 10,
            'weight_cover_letter' => 10,
            'threshold_shortlist' => 70,
            'threshold_reject' => 40,
        ];
    }
}
