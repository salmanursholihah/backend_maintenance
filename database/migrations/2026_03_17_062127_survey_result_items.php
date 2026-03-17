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

        $table->enum('type', [
            'tool',
            'material',
            'sparepart',
            'component'
        ]);

        $table->string('name');
        $table->integer('qty')->default(1);
        $table->string('unit')->nullable();
        $table->decimal('price', 12, 2)->default(0);
        $table->decimal('subtotal', 12, 2)->default(0);
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
