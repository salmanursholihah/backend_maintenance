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
        Schema::create('report_photos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('report_id')->constrained('maintenance_reports')->cascadeOnDelete();

            $table->string('photo');
            $table->enum('type', ['before', 'after', 'documentation'])->default('documentation');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
