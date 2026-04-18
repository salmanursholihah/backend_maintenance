<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
     protected $guarded = [];

       protected $casts = [
        'booking_date'          => 'date',
        'estimated_total_price' => 'decimal:2',
        'final_total_price'     => 'decimal:2',
        'survey_scheduled_at'   => 'datetime',
        'approved_at'           => 'datetime',
        'started_at'            => 'datetime',
        'completed_at'          => 'datetime',
        'cancelled_at'          => 'datetime',
    ];
 
    // ─── Status Constants ─────────────────────────────────────────
    const STATUS_WAITING_TECHNICIAN          = 'waiting_technician';
    const STATUS_SURVEY_SCHEDULED            = 'survey_scheduled';
    const STATUS_SURVEY_ON_PROGRESS          = 'survey_on_progress';
    const STATUS_WAITING_ESTIMATION_APPROVAL = 'waiting_estimation_approval';
    const STATUS_ESTIMATION_APPROVED         = 'estimation_approved';
    const STATUS_ESTIMATION_REJECTED         = 'estimation_rejected';
    const STATUS_MAINTENANCE_ON_PROGRESS     = 'maintenance_on_progress';
    const STATUS_COMPLETED                   = 'completed';
    const STATUS_CANCELLED                   = 'cancelled';
 
    const PAYMENT_UNPAID   = 'unpaid';
    const PAYMENT_PENDING  = 'pending';
    const PAYMENT_PAID     = 'paid';
    const PAYMENT_FAILED   = 'failed';
    const PAYMENT_REFUNDED = 'refunded';
 
    // ─── Boot ─────────────────────────────────────────────────────
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'BK-' . strtoupper(uniqid());
            }
        });
    }
 
    // ─── Helpers ──────────────────────────────────────────────────
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_WAITING_TECHNICIAN,
            self::STATUS_SURVEY_SCHEDULED,
            self::STATUS_ESTIMATION_REJECTED,
        ]);
    }
 
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // banyak booking milik 1 customer
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    // banyak booking di 1 lokasi
    public function location()
    {
        return $this->belongsTo(MaintenanceLocation::class, 'location_id');
    }
 
    // 1 booking punya banyak detail layanan
    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }
 
    // 1 booking punya banyak penugasan teknisi
    public function bookingTechnicians()
    {
        return $this->hasMany(BookingTechnician::class);
    }
 
    // 1 booking punya 1 teknisi lead
    public function leadTechnician()
    {
        return $this->hasOne(BookingTechnician::class)->where('is_lead', true);
    }
 
    // 1 booking punya 1 hasil survey
    public function surveyResult()
    {
        return $this->hasOne(SurveyResult::class);
    }
 
    // 1 booking punya banyak progress pengerjaan
    public function bookingProgresses()
    {
        return $this->hasMany(BookingProgress::class)->latest('progress_at');
    }
 
    // 1 booking punya 1 laporan akhir
    public function maintenanceReport()
    {
        return $this->hasOne(MaintenanceReport::class);
    }
 
    // 1 booking punya 1 data payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
 
    // 1 booking punya 1 review dari customer
    public function review()
    {
        return $this->hasOne(Review::class);
    }
 
    // 1 booking tercatat di 1 maintenance history
    public function maintenanceHistory()
    {
        return $this->hasOne(MaintenanceHistory::class);
    }
 
    // 1 booking punya 1 chat room
    public function chatRoom()
    {
        return $this->hasOne(ChatRoom::class);
    }
}
