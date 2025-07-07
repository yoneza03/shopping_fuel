<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleReportController extends Controller
{
    public function index()
    {
        // fuelRecords も一緒に取得する（リレーション付き）
        $vehicles = Vehicle::with('fuelRecords')->orderBy('id', 'asc')->get();
        return view('vehicle_report', compact('vehicles'));
    }
}