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
        Schema::create('maintenance_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();
 
            $table->text('report');                       // isi laporan utama
            $table->text('before_condition')->nullable(); // kondisi sebelum dikerjakan
            $table->text('after_condition')->nullable();  // kondisi setelah dikerjakan
            $table->text('action_taken')->nullable();     // tindakan yang dilakukan
            $table->text('recommendation')->nullable();   // rekomendasi perawatan ke depan
 
            $table->enum('condition', [
                'good',            // baik
                'need_attention',  // perlu perhatian
                'critical',        // kritis
            ])->default('good');
 
            $table->integer('work_duration')->nullable(); // durasi pengerjaan aktual (menit)
 
            $table->timestamp('reported_at')->nullable(); // waktu laporan dibuat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_reports');
    }
};
