<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('min_education', ['SMA', 'D3', 'D4', 'S1', 'S2', 'S3'])->nullable();
            $table->unsignedTinyInteger('min_experience_years')->default(0);
            $table->json('required_skills')->nullable();
            $table->unsignedTinyInteger('weight_education')->default(25);
            $table->unsignedTinyInteger('weight_experience')->default(25);
            $table->unsignedTinyInteger('weight_skills')->default(30);
            $table->unsignedTinyInteger('weight_profile')->default(10);
            $table->unsignedTinyInteger('weight_cover_letter')->default(10);
            $table->unsignedTinyInteger('threshold_shortlist')->default(70);
            $table->unsignedTinyInteger('threshold_reject')->default(40);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_criteria');
    }
};
