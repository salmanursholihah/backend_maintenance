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

        $table->enum('status', [
            'assigned',
            'accepted',
            'rejected',
            'working',
            'completed'
        ])->default('assigned');

        $table->text('note')->nullable();
        $table->timestamp('assigned_at')->nullable();
        $table->timestamp('responded_at')->nullable();

        $table->timestamps();

        $table->unique(['booking_id', 'technician_id']);
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
