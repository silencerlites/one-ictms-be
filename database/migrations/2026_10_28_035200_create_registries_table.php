<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('registries',function(Blueprint $table){
  $table->id();$table->string('system_name');$table->string('alias',100)->nullable();
  $table->text('description')->nullable();$table->foreignId('business_owner')->nullable()->constrained('departments')->restrictOnDelete();
  $table->foreignId('status')->constrained('registry_statuses')->restrictOnDelete();$table->timestamps();
 }); }
 public function down(): void { Schema::dropIfExists('registries'); }
};
