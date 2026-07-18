<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        $levels = "'SMA','D3','D4','S1','S2','S3'";

        DB::statement("ALTER TABLE candidate_profiles MODIFY education_level ENUM({$levels}) NULL");
        DB::statement("ALTER TABLE job_criteria MODIFY min_education ENUM({$levels}) NULL");
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        $levels = "'SMA','D3','S1','S2','S3'";

        DB::statement("ALTER TABLE candidate_profiles MODIFY education_level ENUM({$levels}) NULL");
        DB::statement("ALTER TABLE job_criteria MODIFY min_education ENUM({$levels}) NULL");
    }
};
