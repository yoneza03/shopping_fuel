<?php

namespace App\Http\Controllers;

use App\Models\FuelRecord;
use Illuminate\Http\Request;

class FuelRecordController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fuel_amount' => 'required|numeric|min:0.1',
            'distance' => 'required|numeric|min:0.1',
            'date' => 'required|date'
        ]);

        $efficiency = $request->distance / $request->fuel_amount;

        FuelRecord::create([
            'vehicle_id' => 1, // 仮で固定。後で切り替え可
            'user_id' => auth()->id(),
            'fuel_amount' => $request->fuel_amount,
            'distance' => $request->distance,
            'fuel_efficiency' => round($efficiency, 2),
            'created_at' => $request->date,
            'updated_at' => now()
        ]);

        return redirect()->route('fuel.history')->with('message', '燃費データを登録しました！');
    }

}
