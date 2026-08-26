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
            $table->string('uuid', 36)->nullable();
            $table->string('code', 50)->nullable();
            $table->string('title', 150);
            $table->string('link', 255)->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('description', 255)->nullable();
            
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('apply_id')->constrained()->cascadeOnDelete();

            $table->boolean('is_online');
            $table->enum('status', ['PENDING', 'AVAILABLE', 'NOT AVAILABLE'])->default('PENDING');
            $table->timestamps();
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
