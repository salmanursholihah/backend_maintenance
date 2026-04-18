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
 
            $table->text('inspection_result')->nullable();   // hasil pemeriksaan
            $table->text('problem_summary')->nullable();     // ringkasan masalah
            $table->text('recommended_action')->nullable();  // rekomendasi tindakan
 
            $table->integer('estimated_duration')->nullable(); // estimasi waktu pengerjaan (menit)
 
            // Rincian biaya
            $table->decimal('service_cost', 12, 2)->default(0);    // jasa
            $table->decimal('sparepart_cost', 12, 2)->default(0);  // suku cadang
            $table->decimal('other_cost', 12, 2)->default(0);       // lain-lain
            $table->decimal('estimated_total_cost', 12, 2)->default(0); // total
 
            $table->enum('status', [
                'draft',      // teknisi masih mengisi
                'submitted',  // terkirim ke customer
                'approved',   // customer setuju
                'rejected',   // customer tolak
            ])->default('draft');
 
            $table->text('rejection_reason')->nullable(); // alasan penolakan dari customer
 
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
        Schema::dropIfExists('survey_results');
    }
};
