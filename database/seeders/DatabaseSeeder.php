<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'madtive@gmail.com'],
            [
                'name' => 'HR Madtive Studio',
                'email_verified_at' => now(),
                'password' => '12345678',
                'level' => 'admin',
            ]
        );

        Candidate::updateOrCreate(
            ['email' => 'usertest@gmail.com'],
            [
                'name' => 'John Doe',
                'email_verified_at' => now(),
                'password' => '12345678',
                'phone' => '081222534937',
                'birth_date' => '2002-04-27',
            ]
        );

        Company::updateOrCreate(
            ['name' => 'Madtive Studio'],
            [
                'address' => 'Perumahan Rancabali, No 93. Muka, Kec. Cianjur, Kabupaten Cianjur, Jawa Barat 43216',
                'location' => 'Cianjur, Jawa Barat',
            ]
        );

        $this->call(BatchSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(JobSeeder::class);
    }
}
