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
        Schema::create('booking_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();
 
            $table->string('title');                               // judul progress, contoh: "Pembersihan filter selesai"
            $table->text('description')->nullable();              // penjelasan detail
            $table->unsignedTinyInteger('progress_percent')->default(0); // 0 - 100
 
            $table->string('photo')->nullable();                  // path: storage/images/progresses/
 
            $table->timestamp('progress_at')->useCurrent();      // waktu progress ini dicatat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_progress');
    }
};
