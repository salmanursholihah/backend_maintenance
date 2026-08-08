<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    // ─── Status Constants (dari remote) ────────────────────────────
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

    // ─── Boot (dari remote) ─────────────────────────────────────────
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'BK-' . strtoupper(uniqid());
            }
        });
    }

    // ─── Helpers (dari remote) ──────────────────────────────────────
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

    // ─── RELASI DASAR (nama & type hint dari HEAD/lokal) ────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(MaintenanceLocation::class, 'location_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(BookingDetail::class);
    }

    // alias biar kompatibel kalau ada kode lama/lain yang manggil nama remote
    public function bookingDetails(): HasMany
    {
        return $this->details();
    }

    // ─── TECHNICIAN (many-to-many, dari HEAD) ───────────────────────
    public function technicians(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'booking_technicians', 'booking_id', 'technician_id')
            ->withPivot(['status', 'note', 'assigned_at', 'responded_at'])
            ->withTimestamps();
    }

    public function bookingTechnicians(): HasMany
    {
        return $this->hasMany(BookingTechnician::class);
    }

    // tambahan dari remote — belum ada di HEAD
    public function leadTechnician(): HasOne
    {
        return $this->hasOne(BookingTechnician::class)->where('is_lead', true);
    }

    // ─── RELASI LAIN ─────────────────────────────────────────────────
    public function progresses(): HasMany
    {
        // pakai versi remote karena ada urutan ->latest()
        return $this->hasMany(BookingProgress::class, 'booking_id')->latest('progress_at');
    }

    // alias biar kompatibel dengan penamaan remote
    public function bookingProgresses(): HasMany
    {
        return $this->progresses();
    }

    public function surveyResult(): HasOne
    {
        return $this->hasOne(SurveyResult::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(MaintenanceReport::class, 'booking_id');
    }

    // alias biar kompatibel dengan penamaan remote
    public function maintenanceReport(): HasOne
    {
        return $this->report();
    }

    // ─── Tambahan murni dari remote — belum ada sama sekali di HEAD ──
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function maintenanceHistory(): HasOne
    {
        return $this->hasOne(MaintenanceHistory::class);
    }

    public function chatRoom(): HasOne
    {
        return $this->hasOne(ChatRoom::class);
    }
}
