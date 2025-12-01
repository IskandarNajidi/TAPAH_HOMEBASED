<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'address',
        'area_name',
        'property_type',
        'contact',
        'total_units',
        'available_rooms',
        'tenants_needed',
        'current_tenants',
        'contract_duration',
        'gender',
        'furnished_level',
        'total_bathrooms',
        'images',
        'room_overview',
        'price',
        'amenities',
        'distance_ui_tm',
        'nearby_shop',
        'nearby_facility',
        'location_advantages',
    ];

    protected $casts = [
        'images' => 'array',
        'amenities' => 'array',
        'room_overview' => 'array',
        'price' => 'decimal:2',
    ];

    // 🔗 Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function additionalRooms()
    {
        return $this->hasMany(AdditionalRoomDetail::class, 'property_id');
    }

}
