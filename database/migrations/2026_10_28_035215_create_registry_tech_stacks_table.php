<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registry_tech_stacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registry_id')->unique()->constrained('registries')->cascadeOnDelete();
            $table->json('frontend_stack')->nullable();
            $table->json('backend_stack')->nullable();
            $table->string('database')->nullable();
            $table->string('server_type')->nullable();
            $table->string('server_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registry_tech_stacks');
    }
};
