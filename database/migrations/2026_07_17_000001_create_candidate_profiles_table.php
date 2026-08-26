<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('education_level', ['SMA', 'D3', 'D4', 'S1', 'S2', 'S3'])->nullable();
            $table->string('major', 150)->nullable();
            $table->string('university', 150)->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->unsignedTinyInteger('years_of_experience')->default(0);
            $table->string('last_position', 100)->nullable();
            $table->string('last_company', 150)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->unsignedInteger('expected_salary')->nullable();
            $table->date('availability_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_profiles');
    }
};
