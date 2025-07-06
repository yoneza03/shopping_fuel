<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    // 車種登録フォームを表示
    public function index()
    {
        // $vehicles = Vehicle::orderBy('created_at', 'desc')->get();
        $vehicles = Vehicle::orderBy('id', 'asc')->get();
        return view('vehicle_entry', compact('vehicles'));
    }

    // 車種登録処理
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'maker' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . date('Y')
        ]);

        Vehicle::create([
            'name' => $request->name,
            'maker' => $request->maker,
            'year' => $request->year
        ]);

        return redirect()->route('vehicle.index')->with('message', '車種を登録しました！');
    }

}
