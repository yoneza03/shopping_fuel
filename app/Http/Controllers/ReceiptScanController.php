<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReceiptScanController extends Controller
{
    // 📸 カメラ起動画面
    public function scan()
    {
        return view('receipt_scan');
    }

    // 📥 撮影された画像データを受け取り、一時保存＆プレビューへ
    public function store(Request $request)
    {
        $request->validate([
            'image_data' => 'required|string',
        ]);

        // Base64 形式の画像データを保存
        $data = $request->image_data;
        $image = str_replace('data:image/png;base64,', '', $data);
        $image = str_replace(' ', '+', $image);
        $imageName = 'receipt_' . time() . '.png';

        Storage::disk('public')->put('receipts/' . $imageName, base64_decode($image));

        // ファイルパスをビューに渡して表示
        return view('receipt_preview', ['imagePath' => 'storage/receipts/' . $imageName]);
    }
}