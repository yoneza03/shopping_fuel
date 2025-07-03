<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingRecord extends Model
{
    protected $fillable = [
        'store',
        'date',
        'items',
    ];

    // items を自動で配列に変換したいならオプションで追加
    protected $casts = [
        'items' => 'array',
    ];

    public function getItemPricesByName($itemName) {
    return $this->items // 配列 or コレクションとして取得
        ? collect($this->items)->filter(fn($item) => $item['name'] === $itemName)
        : collect([]);
    }

}
