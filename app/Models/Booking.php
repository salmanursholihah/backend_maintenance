<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $guarded = [];

protected $casts = [
        'booking_date' => 'date',
        'estimated_total_price' => 'float',
        'final_total_price' => 'float',
        'survey_scheduled_at' => 'datetime',
        'approved_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(MaintenanceLocation::class, 'location_id');
    }

    public function details()
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function technicians()
    {
        return $this->hasMany(BookingTechnician::class);
    }

    public function surveyResult()
    {
        return $this->hasOne(SurveyResult::class);
    }

    public function surveyResults()
    {
        return $this->hasMany(SurveyResult::class);
    }

    public function progresses()
    {
        return $this->hasMany(BookingProgress::class);
    }

    public function report()
    {
        return $this->hasOne(MaintenanceReport::class);
    }

    public function reports()
    {
        return $this->hasMany(MaintenanceReport::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function histories()
    {
        return $this->hasMany(MaintenanceHistory::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function chatRooms()
    {
        return $this->hasMany(ChatRoom::class);
    }
    }
