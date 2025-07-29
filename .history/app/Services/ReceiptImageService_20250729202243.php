<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;

// 画像前処理専用ファイル

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


        Log::debug('✂️ 商品領域切り出し完了: ' . $croppedPath);

        return $image->crop($cropW, $cropH, $cropX, $cropY);   
    }
    
    // 商品名領域＋日付領域をまとめて抽出に活用
    public function prepareForOCR(string $enhancedPath): string
    {
        return $this->cropProductNameArea($enhancedPath);
    }

    // 日付抽出を専用領域
    public function cropDateArea(Image $image): Image
    {
        $width = $image->width();
        $height = $image->height();

        // 上部領域の中央〜右寄りを狙う
        $cropX = intval($width * 0.5);      // 画像の右半分
        $cropY = intval($height * 0.02);    // ほんの少し上から
        $cropWidth = intval($width * 0.48); // ほぼ右半分
        $cropHeight = intval($height * 0.10); // 上部10%

        Log::debug('🗓️ 日付領域切り出し完了');
        return $image->crop($cropWidth, $cropHeight, $cropX, $cropY);
    }
}