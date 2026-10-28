<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registry_descs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registry_id')->unique()->constrained('registries')->cascadeOnDelete();
            $table->foreignId('managing_type')->nullable()->constrained('managing_types')->nullOnDelete();
            $table->foreignId('managing_office')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('third_party_company')->nullable();
            $table->date('project_roll_on')->nullable();
            $table->date('project_sign_off')->nullable();
            $table->unsignedInteger('estimation_project_duration')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registry_descs');
    }
};
