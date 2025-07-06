<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FuelRecord;

class Vehicle extends Model
{
    protected $fillable = [
        'name',
        'maker',
        'year',
    ];

    // FuelRecordとのリレーション
    public function fuelRecords()
    {
        return $this->hasMany(FuelRecord::class);
    }
}