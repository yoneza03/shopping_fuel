<?php

namespace App\Utils;

class ImageProcessor
{
    /**
     * 色反転フィルター（白背景→黒背景）
     */
    public static function negate(string $sourcePath, string $outputPath): bool
    {
        // 対応形式を PNG／JPEG 両方チェック
        $image = @imagecreatefrompng($sourcePath) 
              ?: @imagecreatefromjpeg($sourcePath) 
              ?: null;

        if (!$image) return false;

        imagefilter($image, IMG_FILTER_NEGATE);
        imagepng($image, $outputPath);
        imagedestroy($image);
        return true;
    }

    // 必要に応じて他の前処理を追加予定
}