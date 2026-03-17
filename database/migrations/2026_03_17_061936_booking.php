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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('location_id')->constrained('maintenance_locations')->cascadeOnDelete();

        $table->string('booking_code')->unique();

        $table->date('booking_date');
        $table->time('booking_time');

        $table->enum('status', [
            'waiting_technician',
            'survey_scheduled',
            'survey_on_progress',
            'waiting_estimation_approval',
            'estimation_approved',
            'estimation_rejected',
            'maintenance_pending',
            'maintenance_on_progress',
            'completed',
            'cancelled'
        ])->default('waiting_technician');

        $table->enum('survey_status', [
            'pending',
            'accepted',
            'rejected',
            'scheduled',
            'done'
        ])->default('pending');

        $table->enum('payment_status', [
            'unpaid',
            'pending',
            'paid',
            'failed'
        ])->default('unpaid');

        $table->text('complaint')->nullable();
        $table->text('customer_note')->nullable();
        $table->text('cancel_reason')->nullable();

        $table->decimal('estimated_total_price', 12, 2)->default(0);
        $table->decimal('final_total_price', 12, 2)->default(0);

        $table->timestamp('survey_scheduled_at')->nullable();
        $table->timestamp('approved_at')->nullable();
        $table->timestamp('started_at')->nullable();
        $table->timestamp('completed_at')->nullable();
        $table->timestamp('cancelled_at')->nullable();

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
