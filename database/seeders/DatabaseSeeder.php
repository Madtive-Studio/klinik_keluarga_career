<?php

namespace Database\Seeders;

use App\Models\{ User, Candidate, Company };
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
                'password' => '12345678',
                'level' => 'admin',
                'updated_at' => date('Y-m-d H:i:s'),
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
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        Company::updateOrCreate(
            ['name' => 'Madtive Studio'],
            [
                'address' => 'Perumahan Rancabali, No 93. Muka, Kec. Cianjur, Kabupaten Cianjur, Jawa Barat 43216',
                'location' => 'Cianjur, Jawa Barat',
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        
        $this->call(BatchSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(JobSeeder::class);
    }
}
