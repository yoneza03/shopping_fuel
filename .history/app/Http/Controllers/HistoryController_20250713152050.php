<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function unifiedHistory(Request $request)
    {
        $type = $request->input('type', []);
        $filters = $request->only(['item_keyword', 'price', 'store', 'date_from', 'date_to']);

        $shopping = [];
        $price = [];
        $fuel = [];

        if (in_array('shopping', $type)) {
            $shopping = $this->filterShopping($filters);
        }
        if (in_array('price', $type)) {
            $price = $this->filterPrice($filters);
        }
        if (in_array('fuel', $type)) {
            $fuel = $this->filterFuel($filters);
        }

        return view('history_overview', compact('shopping', 'price', 'fuel', 'filters'));
    }
}
