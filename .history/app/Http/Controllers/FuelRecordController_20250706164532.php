<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FuelRecord;
use Illuminate\Support\Facades\Log;


class FuelRecordController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fuel_amount' => 'required|numeric|min:0.1',
            'distance' => 'required|numeric|min:0.1',
            'date' => 'required|date'
        ]);
        Log::debug('保存内容', [
            'fuel' => $request->fuel_amount,
            'distance' => $request->distance,
            // 'user' => auth()->id(),
            'user' => 1,
            'date' => $request->date
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

    public function history()
    {
        $records = FuelRecord::orderBy('created_at')->get();

        $labels = $records->pluck('created_at')->map(function ($date) {
            return $date->format('Y-m-d');
        });

        $efficiencies = $records->pluck('fuel_efficiency');

        return view('fuel_history', compact('records', 'labels', 'efficiencies'));
    }

    public function create()
    {
        return view('fuel_entry');
    }

}
