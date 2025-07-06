<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    // 車種登録フォームを表示
    public function index()
    {
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

    // 編集
    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('vehicle_edit', compact('vehicle'));
    }

    // 更新
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'maker' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . date('Y')
        ]);

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($request->only(['name', 'maker', 'year']));

        return redirect()->route('vehicle.index')->with('message', '車種情報を更新しました！');
    }

    // 削除
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return redirect()->route('vehicle.index')->with('message', '車種を削除しました');
    }

}
