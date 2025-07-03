<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceHistoryController extends Controller
{
    public function show($itemName)
    {
        $records = ShoppingRecord::whereJsonContains('items', [['name' => $itemName]])
            ->orderBy('date', 'asc')
            ->get();

        $history = [];

        foreach ($records as $record) {
            foreach ($record->items as $item) {
                if ($item['name'] === $itemName) {
                    $history[] = [
                        'date' => $record->date,
                        'price' => $item['price'],
                        'store' => $record->store,
                    ];
                }
            }
        }

        return view('price_history', compact('itemName', 'history'));
    }
}
