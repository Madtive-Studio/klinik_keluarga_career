<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    // Supaya tidak duplikat saat di-seed berulang
    private static array $categories = [
        'Teknologi Informasi',
        'Desain & Kreatif',
        'Marketing & Komunikasi',
        'Keuangan & Akuntansi',
        'Operasional & Umum',
        'Sumber Daya Manusia',
        'Hukum & Kepatuhan',
    ];

    public function definition(): array
    {
        return [
            'name'       => $this->faker->unique()->randomElement(self::$categories),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}