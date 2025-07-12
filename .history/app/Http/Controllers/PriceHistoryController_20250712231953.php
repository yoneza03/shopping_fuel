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

    public function export($itemName)
    {
        $records = ShoppingRecord::whereJsonContains('items', [['name' => $itemName]])
            ->orderBy('date', 'asc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"price_history_{$itemName}.csv\"",
        ];

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

        return response()->stream(function () use ($history) {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, ['購入日', '価格', '店舗']);

            foreach ($history as $row) {
                fputcsv($stream, [
                    $row['date'],
                    '¥' . number_format($row['price']),
                    $row['store']
                ]);
            }

            fclose($stream);
        }, 200, $headers);
    }
}
