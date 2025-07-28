<!-- 画像前処理専用 -->
<?php

// 名前空間を付けることで、LaravelのDI（依存注入）で呼び出し可
namespace App\Services; 

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;

class ReceiptImageService
{
    public function cropProductNameArea(string $imagePath): string
    {
        $image = Image::make($imagePath);

        // 画像サイズを取得して割合でクロップ（動的対応）
        $width = $image->width();
        $height = $image->height();
    
        // 商品領域の想定（中央〜下部）
        $cropX = intval($width * 0.05);  // 左から5%
        $cropY = intval($height * 0.4); // 上から40%
        $cropW = intval($width * 0.9);  // 幅の90%
        $cropH = intval($height * 0.5); // 高さの50%

        $croppedPath = public_path('tmp/cropped_product_' . uniqid() . '.jpg');
        $image->crop($cropW, $cropH, $cropX, $cropY)->save($croppedPath);

        Log::debug('✂️ 商品領域切り出し完了: ' . $croppedPath);

        return $croppedPath;
    }
    
    // 商品名領域＋日付領域をまとめて抽出に活用
    public function prepareForOCR(string $enhancedPath): string
    {
        return $this->cropProductNameArea($enhancedPath);
    }
}