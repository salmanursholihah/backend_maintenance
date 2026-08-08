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
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            
            // booking_id nullable: room bisa dibuat tanpa booking (jika perlu support chat umum)
            // nullOnDelete: jika booking dihapus, room tetap ada tapi booking_id jadi null
            $table->foreignId('booking_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
 
            $table->foreignId('customer_id')
                ->constrained('users')
                ->cascadeOnDelete();
 
            $table->foreignId('technician_id')
                ->constrained('users')
                ->cascadeOnDelete();
 
            // 1 kombinasi booking + customer + teknisi hanya 1 room
            $table->unique(['booking_id', 'customer_id', 'technician_id']);
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
