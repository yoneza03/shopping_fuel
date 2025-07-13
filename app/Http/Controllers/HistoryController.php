<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingRecord;
use App\Models\FuelRecord;

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

    //買い物履歴
    private function filterShopping(array $filters)
    {
        $query = ShoppingRecord::query();

        // 店舗名の絞り込み
        if (!empty($filters['store'])) {
            $query->where('store', 'like', '%' . $filters['store'] . '%');
        }

        // 日付範囲
        if (!empty($filters['date_from'])) {
            $query->where('date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('date', '<=', $filters['date_to']);
        }

        $records = $query->orderBy('date', 'desc')->get();

        // 商品名と価格によるフィルター（items内）
        $filtered = [];

        foreach ($records as $record) {
            foreach ($record->items as $item) {
                $match = true;

                if (!empty($filters['item_keyword']) && stripos($item['name'], $filters['item_keyword']) === false) {
                    $match = false;
                }

                if (!empty($filters['price_min']) && floatval($item['price']) < floatval($filters['price_min'])) {
                    $match = false;
                }

                if ($match) {
                    $filtered[] = [
                        'store' => $record->store,
                        'date' => $record->date,
                        'name' => $item['name'],
                        'price' => intval($item['price']),
                    ];
                }
            }
        }

        return $filtered;
    }
    
    //価格変動履歴
    private function filterPrice(array $filters)
    {
        $query = ShoppingRecord::query();

        // 日付・店舗でレコード単位フィルター
        if (!empty($filters['store'])) {
            $query->where('store', 'like', '%' . $filters['store'] . '%');
        }
        if (!empty($filters['date_from'])) {
            $query->where('date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('date', '<=', $filters['date_to']);
        }

        $records = $query->orderBy('date', 'desc')->get();
        $results = [];

        foreach ($records as $record) {
            foreach ($record->items as $item) {
                $match = true;

                // 品名のフィルター（完全一致ではなく部分一致）
                if (!empty($filters['item_keyword']) && stripos($item['name'], $filters['item_keyword']) === false) {
                    $match = false;
                }

                // 価格フィルター
                if (!empty($filters['price_min']) && floatval($item['price']) < floatval($filters['price_min'])) {
                    $match = false;
                }

                if ($match) {
                    $results[] = [
                        'date' => $record->date,
                        'store' => $record->store,
                        'name' => $item['name'],
                        'price' => intval($item['price']),
                    ];
                }
            }
        }

        return $results;
    }

    //燃費履歴
    private function filterFuel(array $filters)
    {
        $query = \App\Models\FuelRecord::query();

        // 日付絞り込み（給油日＝created_atとする）
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // 給油量・燃費・距離などのフィルターを追加する場合もここ！

        return $query->orderBy('created_at', 'desc')->get()->map(function ($r) {
            return [
                'date' => $r->created_at->format('Y-m-d'),
                'distance' => $r->distance,
                'fuel_amount' => $r->fuel_amount,
                'efficiency' => number_format($r->fuel_efficiency, 2),
            ];
        })->toArray();
    }
}
