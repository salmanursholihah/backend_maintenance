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
        Schema::create('booking_technicians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();
 
            $table->boolean('is_lead')->default(false); // apakah dia teknisi utama
 
            $table->enum('status', [
                'assigned',   // baru di-assign admin
                'accepted',   // teknisi menerima
                'rejected',   // teknisi menolak
                'working',    // sedang mengerjakan
                'completed',  // selesai
            ])->default('assigned');
 
            $table->text('note')->nullable();            // catatan dari teknisi saat respond
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('responded_at')->nullable();
 
            $table->unique(['booking_id', 'technician_id']); // 1 teknisi 1x per booking
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_technicians');
    }
};
