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
 
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];
 
    // ─── Role Helpers ──────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
 
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
 
    public function isTechnician(): bool
    {
        return $this->role === 'technician';
    }
 
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : null;
    }
 
    // ─── Relationships ─────────────────────────────────────────────
 
    // 1 user (customer) punya banyak lokasi IPAL
    public function maintenanceLocations()
    {
        return $this->hasMany(MaintenanceLocation::class);
    }
 
    // 1 user (customer) punya banyak booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
 
    // 1 user (technician) bisa di-assign ke banyak booking
    public function bookingTechnicians()
    {
        return $this->hasMany(BookingTechnician::class, 'technician_id');
    }
 
    // 1 user (technician) buat banyak survey result
    public function surveyResults()
    {
        return $this->hasMany(SurveyResult::class, 'technician_id');
    }
 
    // 1 user (technician) buat banyak progress
    public function bookingProgresses()
    {
        return $this->hasMany(BookingProgress::class, 'technician_id');
    }
 
    // 1 user (technician) buat banyak laporan
    public function maintenanceReports()
    {
        return $this->hasMany(MaintenanceReport::class, 'technician_id');
    }
 
    // 1 user (customer) buat banyak review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
 
    // 1 user (customer) punya banyak chat room
    public function chatRoomsAsCustomer()
    {
        return $this->hasMany(ChatRoom::class, 'customer_id');
    }
 
    // 1 user (technician) punya banyak chat room
    public function chatRoomsAsTechnician()
    {
        return $this->hasMany(ChatRoom::class, 'technician_id');
    }
}
