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
        Schema::create('job_criteria_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_criteria_id')->constrained('job_criteria')->cascadeOnDelete();
            $table->string('skill_name', 100);
            $table->timestamps();
        });

        // Migrate existing JSON data from job_criteria.required_skills
        $criterias = DB::table('job_criteria')->whereNotNull('required_skills')->get();

        foreach ($criterias as $criteria) {
            $skills = json_decode($criteria->required_skills, true);
            if (!is_array($skills)) {
                continue;
            }

            foreach ($skills as $skill) {
                if (empty($skill)) {
                    continue;
                }

                DB::table('job_criteria_skills')->insert([
                    'job_criteria_id' => $criteria->id,
                    'skill_name' => $skill,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Drop required_skills column from job_criteria table
        Schema::table('job_criteria', function (Blueprint $table) {
            $table->dropColumn('required_skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_criteria', function (Blueprint $table) {
            $table->json('required_skills')->nullable()->after('min_experience_years');
        });

        // Migrate data back from job_criteria_skills to job_criteria.required_skills
        $skillsGroup = DB::table('job_criteria_skills')->get()->groupBy('job_criteria_id');

        foreach ($skillsGroup as $criteriaId => $items) {
            $names = $items->pluck('skill_name')->toArray();

            DB::table('job_criteria')->where('id', $criteriaId)->update([
                'required_skills' => json_encode($names),
            ]);
        }

        Schema::dropIfExists('job_criteria_skills');
    }
};
