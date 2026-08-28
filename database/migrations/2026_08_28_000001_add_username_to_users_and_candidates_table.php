<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username', 50)->nullable()->unique()->after('email');
            }
        });

        Schema::table('candidates', function (Blueprint $table) {
            if (!Schema::hasColumn('candidates', 'username')) {
                $table->string('username', 50)->nullable()->unique()->after('email');
            }
        });

        // Auto-generate usernames for existing records
        $users = DB::table('users')->whereNull('username')->get();
        foreach ($users as $user) {
            $baseUsername = Str::slug(explode('@', $user->email)[0], '_') ?: 'user_' . $user->id;
            $username = $baseUsername;
            $counter = 1;
            while (DB::table('users')->where('username', $username)->where('id', '!=', $user->id)->exists()) {
                $username = $baseUsername . '_' . $counter++;
            }
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        }

        $candidates = DB::table('candidates')->whereNull('username')->get();
        foreach ($candidates as $candidate) {
            $baseUsername = Str::slug(explode('@', $candidate->email)[0], '_') ?: 'candidate_' . $candidate->id;
            $username = $baseUsername;
            $counter = 1;
            while (DB::table('candidates')->where('username', $username)->where('id', '!=', $candidate->id)->exists()) {
                $username = $baseUsername . '_' . $counter++;
            }
            DB::table('candidates')->where('id', $candidate->id)->update(['username' => $username]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn('username');
            }
        });

        Schema::table('candidates', function (Blueprint $table) {
            if (Schema::hasColumn('candidates', 'username')) {
                $table->dropColumn('username');
            }
        });
    }
};
