<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Teknologi Informasi',
            'Desain & Kreatif',
            'Marketing & Komunikasi',
            'Keuangan & Akuntansi',
            'Operasional & Umum',
            'Sumber Daya Manusia',
            'Hukum & Kepatuhan',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}