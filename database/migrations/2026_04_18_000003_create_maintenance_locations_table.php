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
        Schema::create('maintenance_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
 
            $table->string('location_name');
            $table->text('address');
            $table->string('latitude', 30)->nullable();
            $table->string('longitude', 30)->nullable();
 
            // Info spesifik IPAL
            $table->string('ipal_type')->nullable();        // biofilter, anaerob, SBR, dll
            $table->string('capacity')->nullable();          // contoh: "5 m³/hari"
            $table->string('installation_type')->nullable(); // tanam / portable / semi-permanen
 
            // Jadwal maintenance berkala
            $table->date('next_maintenance_date')->nullable();
            $table->integer('maintenance_interval_days')->default(90); // tiap 90 hari
 
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_locations');
    }
};
