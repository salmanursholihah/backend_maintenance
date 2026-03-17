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
    Schema::create('booking_details', function (Blueprint $table) {
        $table->id();

        $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
        $table->foreignId('service_id')->constrained()->cascadeOnDelete();

        $table->decimal('price', 12, 2)->default(0);
        $table->integer('qty')->default(1);
        $table->decimal('subtotal', 12, 2)->default(0);

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
