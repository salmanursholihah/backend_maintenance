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
        'booking_date' => 'date',
        'estimated_total_price' => 'decimal:2',
        'final_total_price' => 'decimal:2',
        'survey_scheduled_at' => 'datetime',
        'approved_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // =========================
    // RELASI DASAR
    // =========================
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

    // =========================
    // TECHNICIAN (many-to-many via booking_technicians)
    // =========================
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

    // =========================
    // RELASI LAIN (kalau ada tabelnya)
    // =========================
    public function progresses()
    {
        return $this->hasMany(BookingProgress::class, 'booking_id');
    }

    public function surveyResult(): HasOne
    {
        return $this->hasOne(SurveyResult::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(MaintenanceReport::class, 'booking_id');
    }
}





