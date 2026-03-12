<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // \App\Models\User::factory(10)->create();

    // \App\Models\User::factory()->create([
    //     'name' => 'Test User',
    //     'email' => 'test@example.com',
    // ]);

    User::updateOrCreate(
      ['email' => 'madtive@gmail.com'],
      [
        'name' => 'HR Madtive Studio',
        'password' => '12345678',
        'level' => 'admin',
        'updated_at' => date('Y-m-d H:i:s'),
      ]
    );

    Candidate::create([
      'name' => 'John Doe',
      'email' => 'johndoe@gmail.com',
      'email_verified_at' => now(),
      'password' => '12345678',
      'phone' => '081222534937',
      'birth_date' => '2002-04-27',
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s'),
    ]);

    Company::create([
      'name' => 'Madtive Studio',
      'address' => 'Perumahan Rancabali, No 93. Muka, Kec. Cianjur, Kabupaten Cianjur, Jawa Barat 43216',
      'location' => 'Cianjur, Jawa Barat',
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s'),
    ]);
  }
}
