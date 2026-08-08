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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
 
            // order_id yang dikirim ke Midtrans
            // format: IPAL-{booking_code}-{timestamp}
            $table->string('order_id')->unique();
            $table->decimal('amount', 12, 2);
 
            $table->enum('status', [
                'pending',   // transaksi dibuat, belum bayar
                'paid',      // sudah lunas
                'failed',    // gagal / ditolak
                'expired',   // kadaluarsa
                'refunded',  // dikembalikan
            ])->default('pending');
 
            // Field dari response Midtrans
            $table->string('payment_type')->nullable();     // bank_transfer, gopay, qris, dll
            $table->string('transaction_id')->nullable();   // transaction_id dari Midtrans
            $table->string('va_number')->nullable();        // nomor VA untuk bank transfer
            $table->string('payment_code')->nullable();     // kode bayar (Indomaret, Alfamart)
            $table->text('snap_token')->nullable();         // token untuk Snap popup / redirect
            $table->json('payment_metadata')->nullable();   // full JSON response dari Midtrans
 
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
