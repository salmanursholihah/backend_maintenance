<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE bookings
            MODIFY COLUMN status ENUM(
                'waiting_technician',
                'survey_scheduled',
                'survey_on_progress',
                'survey_rejected',
                'waiting_estimation_approval',
                'estimation_approved',
                'estimation_rejected',
                'maintenance_pending',
                'maintenance_on_progress',
                'completed',
                'cancelled'
            ) NOT NULL DEFAULT 'waiting_technician'
        ");

        DB::statement("
            ALTER TABLE bookings
            MODIFY COLUMN survey_status ENUM(
                'pending',
                'accepted',
                'rejected',
                'scheduled',
                'customer_approved',
                'customer_rejected',
                'in_progress',
                'done'
            ) NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE bookings
            MODIFY COLUMN status ENUM(
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
            ) NOT NULL DEFAULT 'waiting_technician'
        ");

        DB::statement("
            ALTER TABLE bookings
            MODIFY COLUMN survey_status ENUM(
                'pending',
                'accepted',
                'rejected',
                'scheduled',
                'in_progress',
                'done'
            ) NULL DEFAULT 'pending'
        ");
    }
};
