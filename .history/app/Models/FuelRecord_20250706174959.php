<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelRecord extends Model
{
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'fuel_amount',
        'distance',
        'fuel_efficiency',
    ];

        
    // Vehicleとのリレーション
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}