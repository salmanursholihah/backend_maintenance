<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('bank_account_id')->constrained('technician_bank_accounts')->cascadeOnDelete();

            $table->decimal('amount', 12, 2);
            $table->decimal('admin_fee', 12, 2)->default(2500);
            $table->decimal('received_amount', 12, 2);

            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending');
            $table->text('note')->nullable(); // alasan reject dari admin, kalau ada

            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};


