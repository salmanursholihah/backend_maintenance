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
    Schema::create('maintenance_histories', function (Blueprint $table) {
        $table->id();

        $table->foreignId('location_id')->constrained('maintenance_locations')->cascadeOnDelete();
        $table->foreignId('booking_id')->constrained()->cascadeOnDelete();

        $table->date('maintenance_date');
        $table->text('summary')->nullable();

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
