<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $user        = User::where('email', 'madtive@gmail.com')->first();
        $activeBatch = Batch::where('status', 'ACTIVE')->first();
        $categories  = Category::all();

        // Buat job untuk setiap kategori (variasi type)
        $categories->each(function ($category) use ($activeBatch, $user) {
            // 1 fulltime per kategori
            Job::factory()
                ->fulltime()
                ->forBatchAndCategory($activeBatch->id, $category->id)
                ->create(['user_id' => $user->id]);

            // 1 random type per kategori
            Job::factory()
                ->forBatchAndCategory($activeBatch->id, $category->id)
                ->create(['user_id' => $user->id]);
        });

        // Tambah beberapa internship
        Job::factory()
            ->count(3)
            ->internship()
            ->forBatchAndCategory($activeBatch->id, $categories->first()->id)
            ->create(['user_id' => $user->id]);
    }
}