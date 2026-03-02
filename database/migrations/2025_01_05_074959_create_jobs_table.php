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
            $table->string('uuid')->unique();
            $table->string('title');
            $table->string('qualification');
            $table->longText('description');
            $table->enum('type', ['WFH/Remote', 'Partime/Freelancer', 'Fulltime/Onsite', 'Internship']);
            $table->double('quota');
            $table->string('code');
            $table->string('salary');
            $table->string('experience');
            $table->boolean('is_show_salary');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('batch_id')->references('id')->on('batches');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('user_id')->references('id')->on('users');
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
