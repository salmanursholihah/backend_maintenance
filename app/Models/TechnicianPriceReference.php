<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicianPriceReference extends Model
{
    use HasFactory;
        protected $guarded = [];

 protected $casts = [
        'price' => 'float',
        'is_active' => 'boolean',
    ];

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
