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
            'weight_education' => 30,
            'weight_experience' => 30,
            'weight_skills' => 0,
            'weight_profile' => 20,
            'weight_cover_letter' => 20,
            'threshold_shortlist' => 70,
            'threshold_reject' => 40,
        ];
    }
}
