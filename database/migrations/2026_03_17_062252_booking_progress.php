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
    Schema::create('booking_progresses', function (Blueprint $table) {
        $table->id();

        $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
        $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();

        $table->string('title');
        $table->text('description')->nullable();
        $table->integer('progress_percent')->default(0);

        $table->string('photo')->nullable();

        $table->timestamp('progress_at')->nullable();

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
