<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingRecord;

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

        $prices = collect($history)->pluck('price');
        $dates = collect($history)->pluck('date');

        $summary = [
            'min' => $prices->min(),
            'max' => $prices->max(),
            'avg' => round($prices->avg(), 2),
            'count' => $prices->count(),
            'min_date' => $dates[$prices->search($prices->min())] ?? null,
            'max_date' => $dates[$prices->search($prices->max())] ?? null,
        ];

        $labels = [];
        $prices = [];

        foreach ($history as $row) {
            $labels[] = $row['date'];
            $prices[] = $row['price'];
        }

        return view('price_history', [
            'itemName' => $itemName,
            'history' => $history,
            'labels' => $labels,
            'prices' => $prices,
            'summary' => $summary ?? null,
        ]);
    }
}
