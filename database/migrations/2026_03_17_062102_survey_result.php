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
    Schema::create('survey_results', function (Blueprint $table) {
        $table->id();

        $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
        $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();

        $table->text('inspection_result')->nullable();
        $table->text('problem_summary')->nullable();
        $table->text('recommended_action')->nullable();

        $table->integer('estimated_duration')->nullable(); // menit
        $table->decimal('service_cost', 12, 2)->default(0);
        $table->decimal('sparepart_cost', 12, 2)->default(0);
        $table->decimal('other_cost', 12, 2)->default(0);
        $table->decimal('estimated_total_cost', 12, 2)->default(0);

        $table->enum('status', [
            'draft',
            'submitted',
            'approved',
            'rejected'
        ])->default('draft');

        $table->timestamp('submitted_at')->nullable();
        $table->timestamp('approved_at')->nullable();
        $table->timestamp('rejected_at')->nullable();

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
