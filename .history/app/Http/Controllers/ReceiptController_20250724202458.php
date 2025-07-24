<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{
    public function showForm()
    {
        return view('upload_receipt');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $path = $request->file('receipt_image')->store('receipts', 'public');

        return back()->with('success', '画像アップロードが完了しました！')->with('path', $path);
    }

}
