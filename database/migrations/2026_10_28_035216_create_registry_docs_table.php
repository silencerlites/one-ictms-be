<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('registry_docs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registry_id')->constrained('registries')->cascadeOnDelete();
            $table->foreignId('docs_type')->constrained('document_types')->restrictOnDelete();
            $table->string('file_path', 2048);
            $table->index(['registry_id', 'docs_type']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('registry_docs'); }
};
