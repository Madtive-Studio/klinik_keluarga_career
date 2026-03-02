<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applies', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('batch_id');
            $table->string('cover_letter');
            $table->unsignedBigInteger('cv_id');
            $table->enum('status', ['IN REVIEW', 'NOT SUITABLE', 'SHORTLISTED', 'HIRED']);
            $table->longText('description');
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('candidates');
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->foreign('batch_id')->references('id')->on('batches');
            $table->foreign('cv_id')->references('id')->on('cv');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applies');
    }
};
