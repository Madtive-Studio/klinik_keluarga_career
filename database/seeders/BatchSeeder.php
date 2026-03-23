<?php

namespace Database\Seeders;

use App\Models\Batch;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 2 batch inactive (history)
        Batch::factory()->count(2)->inactive()->create();

        // Buat 1 batch active (yang sedang berjalan)
        Batch::factory()->active()->create([
            'code'       => 'BATCH-2025-01',
            'name'       => 'Rekrutmen Batch 1 2025',
            'start_date' => '2025-01-01 00:00:00',
            'end_date'   => '2025-06-30 23:59:59',
            'quota'      => 50,
        ]);
    }
}