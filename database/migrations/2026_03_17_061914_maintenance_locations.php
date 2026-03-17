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
    Schema::create('maintenance_locations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        $table->string('location_name');
        $table->text('address');

        $table->string('latitude')->nullable();
        $table->string('longitude')->nullable();

        $table->string('ipal_type')->nullable();
        $table->string('capacity')->nullable();
        $table->string('installation_type')->nullable(); // tanam / portable / dll

        $table->text('description')->nullable();

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
