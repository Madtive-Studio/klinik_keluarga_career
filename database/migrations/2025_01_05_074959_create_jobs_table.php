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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 36)->unique();
            $table->string('title', 150);
            $table->longText('qualification');
            $table->longText('description');
            $table->enum('type', ['WFH/Remote', 'Partime/Freelancer', 'Fulltime/Onsite', 'Internship']);
            $table->double('quota');
            $table->string('code', 50);
            $table->string('salary', 100);
            $table->string('experience', 100);
            $table->boolean('is_show_salary');
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
