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
        Schema::create('survey_result_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_result_id')->constrained()->cascadeOnDelete();
 
            // opsional: referensi ke master data components
            // jika null, teknisi mengisi manual
            $table->foreignId('component_id')
                ->nullable()
                ->constrained('components')
                ->nullOnDelete();
 
            $table->enum('type', ['tool', 'material', 'sparepart', 'component']);
            $table->string('name');          // bisa override nama dari component
            $table->integer('qty')->default(1);
            $table->string('unit')->nullable(); // pcs, meter, liter, dll
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0); // price * qty
            $table->text('description')->nullable();
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_result_items');
    }
};
