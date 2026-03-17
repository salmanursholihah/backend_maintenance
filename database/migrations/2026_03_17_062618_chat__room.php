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

        $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();

        $table->timestamps();

        $table->unique(['booking_id', 'customer_id', 'technician_id']);
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
