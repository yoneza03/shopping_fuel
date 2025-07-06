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

    // 燃費記録とのリレーション
    public function fuelRecords()
    {
        return $this->hasMany(FuelRecord::class);
    }
}