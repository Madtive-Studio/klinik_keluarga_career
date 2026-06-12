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
        Schema::table('candidates', function (Blueprint $table) {
            // Kelengkapan Identitas fields
            $table->string('ktp_number')->nullable()->after('address')->comment('Nomor KTP');
            $table->string('passport_number')->nullable()->after('ktp_number')->comment('Nomor Passport');
            $table->string('driving_license_number')->nullable()->after('passport_number')->comment('Nomor SIM');
            $table->string('gender')->nullable()->after('driving_license_number')->comment('Jenis Kelamin');
            $table->text('education_background')->nullable()->after('gender')->comment('Latar Belakang Pendidikan');
            $table->text('work_experience')->nullable()->after('education_background')->comment('Pengalaman Kerja');
            
            // Status tracking
            $table->boolean('identity_verified')->default(false)->after('work_experience')->comment('Status Verifikasi Identitas');
            $table->boolean('document_completed')->default(false)->after('identity_verified')->comment('Status Kelengkapan Dokumen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'ktp_number',
                'passport_number',
                'driving_license_number',
                'gender',
                'education_background',
                'work_experience',
                'identity_verified',
                'document_completed',
            ]);
        });
    }
};
