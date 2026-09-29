<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add min_education column to jobs table
        if (!Schema::hasColumn('jobs', 'min_education')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->string('min_education', 50)->nullable()->after('experience');
            });
        }

        // 2. Migrate existing min_education data from job_criteria to jobs
        if (Schema::hasTable('job_criteria')) {
            $criterias = DB::table('job_criteria')->whereNotNull('min_education')->get();
            foreach ($criterias as $criteria) {
                DB::table('jobs')->where('id', $criteria->job_id)->update([
                    'min_education' => $criteria->min_education,
                ]);
            }
        }

        // 3. Drop child table job_criteria_skills first, then job_criteria
        Schema::dropIfExists('job_criteria_skills');
        Schema::dropIfExists('job_criteria');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('job_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('min_education', 50)->nullable();
            $table->unsignedTinyInteger('min_experience_years')->default(0);
            $table->unsignedTinyInteger('weight_education')->default(25);
            $table->unsignedTinyInteger('weight_experience')->default(25);
            $table->unsignedTinyInteger('weight_skills')->default(30);
            $table->unsignedTinyInteger('weight_profile')->default(10);
            $table->unsignedTinyInteger('weight_cover_letter')->default(10);
            $table->unsignedTinyInteger('threshold_shortlist')->default(70);
            $table->unsignedTinyInteger('threshold_reject')->default(40);
            $table->timestamps();
        });

        Schema::create('job_criteria_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_criteria_id')->constrained('job_criteria')->cascadeOnDelete();
            $table->string('skill_name', 100);
            $table->timestamps();
        });

        // Copy back min_education
        $jobs = DB::table('jobs')->whereNotNull('min_education')->get();
        foreach ($jobs as $job) {
            DB::table('job_criteria')->insert([
                'job_id' => $job->id,
                'min_education' => $job->min_education,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('min_education');
        });
    }
};
