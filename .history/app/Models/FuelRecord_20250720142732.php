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
        'recorded_at', 
        'updated_at',
    ];

    protected $casts = [
    'recorded_at' => 'date',
    ];

        
    // Vehicleとのリレーション
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}