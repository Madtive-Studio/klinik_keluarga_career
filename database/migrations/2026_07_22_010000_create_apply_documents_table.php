<?php

use App\Enums\DocumentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apply_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apply_id')->constrained('applies')->cascadeOnDelete();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->string('type');
            $table->timestamps();

            $table->unique(['apply_id', 'document_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apply_documents');
    }
};
