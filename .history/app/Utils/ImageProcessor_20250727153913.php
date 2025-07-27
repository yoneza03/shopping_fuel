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

    public static function deskewAndDenoise(string $sourcePath, string $outputPath): bool
    {
        $image = @imagecreatefromjpeg($sourcePath) ?: null;
        if (!$image) return false;

        imagefilter($image, IMG_FILTER_SMOOTH, 5);     // ノイズ除去
        imagefilter($image, IMG_FILTER_CONTRAST, -20); // コントラスト強調
        imagepng($image, $outputPath);
        imagedestroy($image);
        return true;
    }

    public static function enhance(string $sourcePath, string $outputPath): bool
    {
        $image = @imagecreatefrompng($sourcePath)
            ?: @imagecreatefromjpeg($sourcePath)
            ?: null;

        if (!$image) return false;

        // 🔁 色反転
        imagefilter($image, IMG_FILTER_NEGATE);

        // ✨ コントラスト強調（数値は調整可）
        imagefilter($image, IMG_FILTER_CONTRAST, -20);

        // 💧 平滑化でノイズ除去
        imagefilter($image, IMG_FILTER_SMOOTH, 5);

        // 🔄 回転補正（仮に -2度。必要に応じて検出ロジックと連携可）
        $image = imagerotate($image, -2, 0);

        imagepng($image, $outputPath);
        imagedestroy($image);
        return true;
    }
    // 必要に応じて他の前処理を追加予定
}