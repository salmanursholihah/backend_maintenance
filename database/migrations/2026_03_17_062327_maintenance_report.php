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
    Schema::create('maintenance_reports', function (Blueprint $table) {
        $table->id();

        $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
        $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();

        $table->text('report');
        $table->text('before_condition')->nullable();
        $table->text('after_condition')->nullable();
        $table->text('action_taken')->nullable();
        $table->text('recommendation')->nullable();

        $table->enum('condition', [
            'good',
            'need_attention',
            'critical'
        ])->default('good');

        $table->integer('work_duration')->nullable(); // menit

        $table->timestamp('reported_at')->nullable();

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
