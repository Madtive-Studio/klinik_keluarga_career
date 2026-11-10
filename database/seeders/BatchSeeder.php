<?php

namespace Database\Seeders;

use App\Models\Batch;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    public function run(): void
    {
        Batch::updateOrCreate(
            ['code' => 'BATCH-2025-01'],
            [
                'name'       => 'Rekrutmen Batch 1 2025',
                'start_date' => '2025-01-01 00:00:00',
                'end_date'   => '2025-06-30 23:59:59',
                'quota'      => 50,
                'status'     => 'INACTIVE',
            ]
        );

        Batch::updateOrCreate(
            ['code' => 'BATCH-2025-02'],
            [
                'name'       => 'Rekrutmen Batch 2 2025',
                'start_date' => '2025-07-01 00:00:00',
                'end_date'   => '2025-12-31 23:59:59',
                'quota'      => 40,
                'status'     => 'INACTIVE',
            ]
        );

        Batch::updateOrCreate(
            ['code' => 'BATCH-2026-01'],
            [
                'name'       => 'Rekrutmen Batch 1 2026',
                'start_date' => now()->subMonths(6)->startOfMonth()->format('Y-m-d H:i:s'),
                'end_date'   => now()->addMonths(6)->endOfMonth()->endOfDay()->format('Y-m-d H:i:s'),
                'quota'      => 50,
                'status'     => 'ACTIVE',
            ]
        );
    }
}