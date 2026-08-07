<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technician_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();

            $table->enum('type', ['bank', 'e_wallet']);
            $table->string('provider'); // BCA, Mandiri, GoPay, DANA, dll
            $table->string('account_number');
            $table->string('account_name');
            $table->boolean('is_default')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technician_bank_accounts');
    }
};


