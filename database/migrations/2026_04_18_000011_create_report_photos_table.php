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
          
            // FK ke maintenance_reports
            $table->foreignId('report_id')
                ->constrained('maintenance_reports')
                ->cascadeOnDelete();
 
            $table->string('photo');          // path: storage/images/report_photos/
            $table->enum('type', [
                'before',          // foto kondisi sebelum
                'after',           // foto kondisi sesudah
                'documentation',   // foto dokumentasi proses
            ])->default('documentation');
            $table->string('caption')->nullable(); // keterangan foto
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_photos');
    }
};
