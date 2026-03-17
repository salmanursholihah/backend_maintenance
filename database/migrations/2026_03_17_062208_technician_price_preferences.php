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
    Schema::create('technician_price_references', function (Blueprint $table) {
        $table->id();

        $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();

        $table->string('component_name');
        $table->string('damage_level')->nullable();
        $table->string('work_type')->nullable();
        $table->decimal('price', 12, 2)->default(0);
        $table->text('description')->nullable();
        $table->boolean('is_active')->default(true);

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
