<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('level', 'admin')->first();
        $activeBatch = Batch::where('status', 'ACTIVE')->first();
        $categories = Category::all();

        if (!$user) {
            throw new RuntimeException('Admin user not found. Pastikan DatabaseSeeder dijalankan terlebih dahulu.');
        }

        if (!$activeBatch) {
            throw new RuntimeException('Batch ACTIVE not found. Pastikan BatchSeeder dijalankan terlebih dahulu.');
        }

        if ($categories->isEmpty()) {
            throw new RuntimeException('Category not found. Pastikan CategorySeeder dijalankan terlebih dahulu.');
        }

        $categories->each(function ($category) use ($activeBatch, $user) {
            Job::factory()
                ->fulltime()
                ->forBatchAndCategory($activeBatch->id, $category->id)
                ->create(['user_id' => $user->id]);

            Job::factory()
                ->forBatchAndCategory($activeBatch->id, $category->id)
                ->create(['user_id' => $user->id]);
        });

        Job::factory()
            ->count(3)
            ->internship()
            ->forBatchAndCategory($activeBatch->id, $categories->first()->id)
            ->create(['user_id' => $user->id]);
    }
}
