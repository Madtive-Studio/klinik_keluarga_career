<?php

namespace Database\Factories;

use App\Enums\JobType;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobFactory extends Factory
{
    protected $model = Job::class;

    private static array $jobTitles = [
        'Backend Developer',
        'Frontend Developer',
        'Mobile Developer',
        'UI/UX Designer',
        'DevOps Engineer',
        'Data Analyst',
        'Digital Marketing Specialist',
        'Staff Akuntansi',
        'IT Support',
        'Project Manager',
        'QA Engineer',
        'Business Analyst',
    ];

    private static array $experiences = [
        'Fresh Graduate',
        '1 Tahun',
        '2 Tahun',
        '3 Tahun',
        '5 Tahun ke atas',
    ];

    public function definition(): array
    {
        $title = $this->faker->randomElement(self::$jobTitles);
        $code  = strtoupper(Str::substr(preg_replace('/[^A-Za-z]/', '', $title), 0, 3))
                 . '-' . $this->faker->numberBetween(100, 999);

        return [
            'uuid'           => (string) Str::uuid(),
            'title'          => $title,
            'code'           => $code,
            'type'           => $this->faker->randomElement(JobType::getValues()),
            'quota'          => $this->faker->numberBetween(1, 5),
            'salary'         => 'Rp ' . number_format($this->faker->numberBetween(3, 8) * 1000000, 0, ',', '.') . ' - Rp ' . number_format($this->faker->numberBetween(9, 15) * 1000000, 0, ',', '.'),
            'experience'     => $this->faker->randomElement(self::$experiences),
            'is_show_salary' => $this->faker->boolean(),
            'qualification'  => implode("\n", [
                '- ' . $this->faker->sentence(),
                '- ' . $this->faker->sentence(),
                '- ' . $this->faker->sentence(),
                '- ' . $this->faker->sentence(),
            ]),
            'description'    => $this->faker->paragraphs(3, true),
            'batch_id'       => Batch::factory()->active(),
            'category_id'    => Category::factory(),
            'user_id'        => User::factory(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ];
    }

    // State untuk job fulltime
    public function fulltime(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => JobType::FULLTIME_ONSITE->value,
        ]);
    }

    // State untuk job internship
    public function internship(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'       => JobType::INTERNSHIP->value,
            'experience' => 'Fresh Graduate',
        ]);
    }

    // State untuk pakai batch & category yang sudah ada (tidak buat baru)
    public function forBatchAndCategory(int $batchId, int $categoryId): static
    {
        return $this->state(fn (array $attributes) => [
            'batch_id'    => $batchId,
            'category_id' => $categoryId,
        ]);
    }
}