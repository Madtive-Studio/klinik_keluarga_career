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
        Schema::create('candidate_identities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('identity_type')->comment('Tipe Identitas: KTP, PASSPORT, SIM, NIK');
            $table->string('identity_number')->comment('Nomor Identitas');
            $table->string('document_file')->nullable()->comment('File pendukung identitas');
            $table->enum('status', ['PENDING', 'VERIFIED', 'REJECTED', 'UNDER_REVIEW'])->default('PENDING');
            $table->text('verification_notes')->nullable()->comment('Catatan dari verifikator');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique(['candidate_id', 'identity_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_identities');
    }
};
