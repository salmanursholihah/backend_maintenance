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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained('maintenance_locations')->cascadeOnDelete();
 
            $table->string('booking_code', 30)->unique();
            $table->date('booking_date');
            $table->time('booking_time');
 
            $table->enum('status', [
                'waiting_technician',           // baru masuk
                'survey_scheduled',             // teknisi di-assign, belum survey
                'survey_on_progress',           // teknisi sedang survey
                'waiting_estimation_approval',  // estimasi dikirim, tunggu customer
                'estimation_approved',          // customer setuju, siap dikerjakan
                'estimation_rejected',          // customer tolak
                'maintenance_on_progress',      // sedang dikerjakan
                'completed',                    // selesai
                'cancelled',                    // dibatalkan
            ])->default('waiting_technician');
 
            $table->enum('payment_status', [
                'unpaid',
                'pending',    // transaksi dibuat di Midtrans
                'paid',
                'failed',
                'refunded',
            ])->default('unpaid');
 
            $table->text('complaint')->nullable();       // keluhan customer
            $table->text('customer_note')->nullable();   // catatan tambahan
            $table->text('cancel_reason')->nullable();   // alasan batal
 
            $table->decimal('estimated_total_price', 12, 2)->default(0);
            $table->decimal('final_total_price', 12, 2)->default(0);
 
            // Timestamp per milestone status
            $table->timestamp('survey_scheduled_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
