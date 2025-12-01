<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'room_type',
        'capacity',
        'current_occupancy',
        'bathroom_type',
        'bed_type',
        'furnished_level',
        'gender_preference',
        'rental_mode',
        'monthly_rent',
        'deposit',
    ];

    protected $casts = [
        'monthly_rent' => 'decimal:2',
        'deposit' => 'decimal:2',
    ];

    // 🔗 Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }
}
