<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function search(Request $request)
    {
        $types = $request->input('type', []);
        $filters = $request->only([
            'item_keyword', 'store',
            'price_min', 'date_from', 'date_to'
        ]);

        $results = [];

        if (in_array('shopping', $types)) {
            $results['shopping'] = $this->filterShopping($filters);
        }
        if (in_array('price', $types)) {
            $results['price'] = $this->filterPrice($filters);
        }
        if (in_array('fuel', $types)) {
            $results['fuel'] = $this->filterFuel($filters);
        }

        return view('history', compact('results', 'filters'));
    }

}
