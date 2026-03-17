<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
       protected $guarded = [];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

      // =========================
    // CUSTOMER
    // =========================
    public function locations()
    {
        return $this->hasMany(MaintenanceLocation::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // =========================
    // TECHNICIAN
    // =========================
    public function bookingTechnicians()
    {
        return $this->hasMany(BookingTechnician::class, 'technician_id');
    }

    public function surveyResults()
    {
        return $this->hasMany(SurveyResult::class, 'technician_id');
    }

    public function priceReferences()
    {
        return $this->hasMany(TechnicianPriceReference::class, 'technician_id');
    }

    public function progresses()
    {
        return $this->hasMany(BookingProgress::class, 'technician_id');
    }

    public function maintenanceReports()
    {
        return $this->hasMany(MaintenanceReport::class, 'technician_id');
    }

    public function technicianReviews()
    {
        return $this->hasMany(Review::class, 'technician_id');
    }

    // =========================
    // NOTIFICATION / CHAT
    // =========================
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function customerChatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'customer_id');
    }

    public function technicianChatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'technician_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }
}
