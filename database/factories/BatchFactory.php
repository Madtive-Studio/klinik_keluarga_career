<?php

namespace Database\Factories;

use App\Models\Batch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BatchFactory extends Factory
{
    protected $model = Batch::class;

    public function definition(): array
    {
        $batchNum  = $this->faker->numberBetween(1, 2);
        $startDate = now()->subMonths(6)->startOfMonth();
        $endDate   = now()->addMonths(6)->endOfMonth()->endOfDay();

        return [
            'code'       => 'BATCH-' . now()->format('Y') . '-0' . $batchNum,
            'name'       => 'Rekrutmen Batch ' . $batchNum . ' ' . now()->format('Y'),
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'quota'      => $this->faker->numberBetween(20, 60),
            'status'     => 'INACTIVE',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // State untuk batch yang sedang aktif (dengan end_date di masa depan)
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ACTIVE',
            'start_date' => now()->subMonth()->startOfMonth(),
            'end_date' => now()->addMonths(5)->endOfMonth()->endOfDay(),
        ]);
    }

    // State untuk batch yang tidak aktif
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'INACTIVE',
            'end_date' => now()->addMonths(5)->endOfMonth()->endOfDay(),
        ]);
    }

    // State untuk batch yang sudah expired
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'INACTIVE',
            'end_date' => now()->subDay()->startOfDay(),
        ]);
    }
}