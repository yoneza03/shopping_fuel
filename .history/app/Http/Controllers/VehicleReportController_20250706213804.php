<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VehicleReportController extends Controller
{
    // fuelRecords 取得
    $vehicles = Vehicle::with('fuelRecords')->orderBy('id', 'asc')->get();

    return view('vehicle_report', compact('vehicles'));
}
