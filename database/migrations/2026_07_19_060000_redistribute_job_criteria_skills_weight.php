<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('job_criteria')->orderBy('id')->each(function ($criteria) {
            $skillsWeight = (int) $criteria->weight_skills;

            if ($skillsWeight <= 0) {
                return;
            }

            $profileBonus = (int) ceil($skillsWeight / 2);
            $coverLetterBonus = $skillsWeight - $profileBonus;

            DB::table('job_criteria')->where('id', $criteria->id)->update([
                'weight_profile' => (int) $criteria->weight_profile + $profileBonus,
                'weight_cover_letter' => (int) $criteria->weight_cover_letter + $coverLetterBonus,
                'weight_skills' => 0,
                'required_skills' => json_encode([]),
            ]);
        });
    }

    public function down(): void
    {
        // Weight redistribution is not safely reversible.
    }
};
