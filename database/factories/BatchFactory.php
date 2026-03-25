<?php

namespace Database\Factories;

use App\Models\Batch;
use Illuminate\Database\Eloquent\Factories\Factory;

class BatchFactory extends Factory
{
    protected $model = Batch::class;

    public function definition(): array
    {
        $year      = $this->faker->numberBetween(2023, 2025);
        $batchNum  = $this->faker->numberBetween(1, 2);
        $startDate = "$year-0{$batchNum}-01 00:00:00";
        $endDate   = $batchNum === 1 ? "$year-06-30 23:59:59" : "$year-12-31 23:59:59";

        return [
            'code'       => "BATCH-{$year}-0{$batchNum}",
            'name'       => "Rekrutmen Batch {$batchNum} {$year}",
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'quota'      => $this->faker->numberBetween(20, 60),
            'status'     => 'INACTIVE',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // State untuk batch yang sedang aktif
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ACTIVE',
        ]);
    }

    // State untuk batch yang tidak aktif
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'INACTIVE',
        ]);
    }
}