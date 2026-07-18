<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->json('images')->nullable()->after('code');
        });

        foreach (DB::table('jobs')->whereNotNull('image')->get() as $job) {
            DB::table('jobs')->where('id', $job->id)->update([
                'images' => json_encode([$job->image]),
            ]);
        }

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('image')->nullable()->after('code');
        });

        foreach (DB::table('jobs')->whereNotNull('images')->get() as $job) {
            $images = json_decode($job->images, true) ?: [];
            DB::table('jobs')->where('id', $job->id)->update([
                'image' => $images[0] ?? null,
            ]);
        }

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
