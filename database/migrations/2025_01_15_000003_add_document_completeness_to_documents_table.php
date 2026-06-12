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
        Schema::table('documents', function (Blueprint $table) {
            // Tambah kolom untuk kategori dan status
            $table->string('category')->default('OTHER')->after('type')->comment('Kategori dokumen');
            $table->enum('status', ['PENDING', 'VERIFIED', 'REJECTED', 'UNDER_REVIEW'])->default('PENDING')->after('category')->comment('Status verifikasi dokumen');
            $table->boolean('is_required')->default(false)->after('status')->comment('Apakah dokumen wajib?');
            $table->text('verification_notes')->nullable()->after('is_required')->comment('Catatan verifikasi dari admin');
            $table->timestamp('verified_at')->nullable()->after('verification_notes')->comment('Waktu dokumen diverifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'status',
                'is_required',
                'verification_notes',
                'verified_at',
            ]);
        });
    }
};
