<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            return;
        }

        if ($driver === 'pgsql') {
            $constraintName = 'job_criteria_min_education_check';

            DB::statement("ALTER TABLE job_criteria DROP CONSTRAINT IF EXISTS {$constraintName}");

            DB::statement(
                "ALTER TABLE job_criteria ADD CONSTRAINT {$constraintName} "
                . "CHECK (min_education IN ('SMA', 'D3', 'D4', 'S1', 'S2', 'S3'))"
            );
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            $constraintName = 'job_criteria_min_education_check';

            DB::statement("ALTER TABLE job_criteria DROP CONSTRAINT IF EXISTS {$constraintName}");

            DB::statement(
                "ALTER TABLE job_criteria ADD CONSTRAINT {$constraintName} "
                . "CHECK (min_education IN ('SMA', 'D3', 'S1', 'S2', 'S3'))"
            );
        }
    }
};
