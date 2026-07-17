<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applies', function (Blueprint $table) {
            $table->unsignedTinyInteger('auto_score')->nullable()->after('status');
            $table->enum('score_recommendation', ['SHORTLIST', 'REVIEW', 'REJECT'])->nullable()->after('auto_score');
            $table->json('score_breakdown')->nullable()->after('score_recommendation');
            $table->timestamp('scored_at')->nullable()->after('score_breakdown');
        });
    }

    public function down(): void
    {
        Schema::table('applies', function (Blueprint $table) {
            $table->dropColumn(['auto_score', 'score_recommendation', 'score_breakdown', 'scored_at']);
        });
    }
};
