<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registry_repos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('registry_id')
                ->constrained('registries')
                ->cascadeOnDelete();

            $table->string('repo_scope', 20)->default('single');

            $table->unique(
                ['registry_id', 'repo_scope'],
                'registry_repos_registry_scope_unique'
            );

            $table->string('staging_url', 2048)->nullable();
            $table->string('production_url', 2048)->nullable();
            $table->string('repo_git', 2048)->nullable();

            $table->foreignId('development_solution_type')
                ->nullable()
                ->constrained('development_solution_types')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registry_repos');
    }
};
