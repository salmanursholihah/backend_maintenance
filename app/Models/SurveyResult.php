<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResult extends Model
{
    use HasFactory;
        protected $guarded = [];

protected $casts = [
        'estimated_duration' => 'integer',
        'service_cost' => 'float',
        'sparepart_cost' => 'float',
        'other_cost' => 'float',
        'estimated_total_cost' => 'float',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function items()
    {
        return $this->hasMany(SurveyResultItem::class);
    }
}
