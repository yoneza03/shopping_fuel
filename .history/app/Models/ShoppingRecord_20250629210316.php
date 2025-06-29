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

    // items を自動で配列に変換したいならオプションで追加できます（あとからでもOK）
    protected $casts = [
        'items' => 'array',
    ];

}
