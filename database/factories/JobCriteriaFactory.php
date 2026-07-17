<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\JobCriteria;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobCriteriaFactory extends Factory
{
    protected $model = JobCriteria::class;

    public function definition(): array
    {
        return [
            'job_id' => Job::factory(),
            'min_education' => 'S1',
            'min_experience_years' => 1,
            'required_skills' => ['Komunikasi', 'Microsoft Office'],
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
