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
        Schema::create('schedule_interviews', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->string('code')->nullable();
            $table->string('title');
            $table->string('link')->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('apply_id');
            $table->boolean('is_online');
            $table->enum('status', ['PENDING', 'AVAILABLE', 'NOT AVAILABLE'])->default('PENDING');
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('candidates');
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->foreign('batch_id')->references('id')->on('batches');
            $table->foreign('apply_id')->references('id')->on('applies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_interviews');
    }
};
