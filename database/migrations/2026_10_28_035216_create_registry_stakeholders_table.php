<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registry_stakeholders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registry_id')->constrained('registries')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('stakeholder_type')->constrained('stakeholder_types')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['registry_id', 'user_id', 'stakeholder_type'], 'registry_stakeholder_unique');
            $table->index(['registry_id', 'stakeholder_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registry_stakeholders');
    }
};
