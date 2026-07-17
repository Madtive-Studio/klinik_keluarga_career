<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\CandidateProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateProfileFactory extends Factory
{
    protected $model = CandidateProfile::class;

    public function definition(): array
    {
        return [
            'candidate_id' => Candidate::factory(),
            'education_level' => 'S1',
            'major' => $this->faker->randomElement(['Keperawatan', 'Informatika', 'Manajemen']),
            'university' => $this->faker->company() . ' University',
            'gpa' => $this->faker->randomFloat(2, 2.5, 4),
            'years_of_experience' => $this->faker->numberBetween(0, 10),
            'last_position' => $this->faker->jobTitle(),
            'last_company' => $this->faker->company(),
            'city' => $this->faker->city(),
            'province' => $this->faker->state(),
            'expected_salary' => $this->faker->numberBetween(3000000, 15000000),
            'availability_date' => now()->addMonth(),
        ];
    }
}
