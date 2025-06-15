<?php

namespace App\Http\Controllers;

use App\Models\ShoppingFuel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShoppingFuelController extends Controller
{
    public function index()
    {
    $fuels = DB::table('shopping_fuels')->get();
    return view('shopping_fuel', ['fuels' => $fuels]);
    }

    public function store(Request $request)
    {
        $fuel = ShoppingFuel::create($request->all());
        return response()->json($fuel, 201);
    }

    public function show($id)
    {
        return response()->json(ShoppingFuel::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $fuel = ShoppingFuel::findOrFail($id);
        $fuel->update($request->all());
        return response()->json($fuel);
    }

    public function destroy($id)
    {
        ShoppingFuel::destroy($id);
        return response()->json(null, 204);
    }
}