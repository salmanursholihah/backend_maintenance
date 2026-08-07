<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingProgress extends Model
{
    use HasFactory;

    protected $table = 'booking_progresses';

    protected $guarded = [];


    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}



