<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingRecord;
use thiagoalessio\TesseractOCR\TesseractOCR;

class ShoppingController extends Controller
{
    // 買い物データ入力ページ
    public function entry(Request $request)
    {
        $data = session()->get('shopping');
        return view('shopping_entry', compact('data'));   
    }
    
    // 確認画面
    public function confirm(Request $request)
    {
        $data = $request->except('receipt');

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('tmp'); // 一時保存
            \Log::debug('画像の保存パス: ' . $path);
            \Log::debug('実フルパス: ' . storage_path('app/' . $path));

            $ocrText = (new TesseractOCR(storage_path('app/' . $path)))
                        ->lang('jpn')
                        ->run();

            //  OCR結果をパース（必要に応じて parseReceipt メソッドを用意）
            $parsed = $this->parseReceipt($ocrText);

            // 手入力よりも OCR優先で上書き（お好みで調整OK）
            $data = array_merge($data, $parsed);
        }

        session()->put('shopping', $data); 
        session()->save();
 
        //  セッションの代わりに flash data（1リクエスト限定）を with() で送る
        return redirect()->route('shopping.confirm.view');
    }

    //サブルーチン
    private function parseReceipt(string $text): array
    {
        $store = '';
        $date = '';
        $items = [];

        // 行単位に分解して解析
        $lines = explode("\n", $text);

        foreach ($lines as $line) {
            $line = trim($line);

            // 店舗名を検出
            if ($store === '' && preg_match('/(イオン|トライアル|業務スーパー|ラ・ムー|サンディ)/u', $line, $match)) {
                $store = $match[1];
            }

            // 日付を検出（例: 2025/06/23 → 2025-06-23）
            if ($date === '' && preg_match('/\d{4}[\/\-]\d{2}[\/\-]\d{2}/', $line, $match)) {
                $date = str_replace('/', '-', $match[0]);
            }

            // 品名＋金額（例: たまご 289円）
            if (preg_match('/(.+?)\s+(\d{2,5})円/u', $line, $match)) {
                $items[] = [
                    'name' => $match[1],
                    'price' => $match[2],
                ];
            }
        }

        return [
            'store' => $store,
            'date' => $date,
            'items' => $items,
        ];
    }

    //確認画面の表示専用ページ
    public function confirmView()
    {
        // flashデータ（with）から取得
        $data = session()->get('shopping');
        return view('shopping_confirm', compact('data'));
    }

    // 削除
    public function clear()
    {
        session()->forget('shopping');
        return redirect()->route('shopping.entry')->with('message', '入力内容をクリアしました');
    }

    // 登録
    public function store()
    {
        $data = session()->get('shopping');

        // データベースに保存
        $record = ShoppingRecord::create([
            'store' => $data['store'],
            'date' => $data['date'],
            'items' => json_encode($data['items']),
        ]);

        session()->forget('shopping');
        return redirect()->route('shopping.entry')->with('success', '登録完了しました');
    }

    // 買い物履歴
    public function history(Request $request)
    {
        $history = ShoppingRecord::orderBy('date', 'desc')->get();

        // 検索条件がある場合はフィルタリング
        if ($request->filled('store')) {
            $history = array_filter($history, function ($entry) use ($request) {
                return str_contains($entry['store'], $request->input('store'));
            });
        }

        if ($request->filled('item_keyword')) {
            $keyword = $request->input('item_keyword');

            $history = array_filter($history, function ($entry) use ($keyword) {
                return collect($entry['items'] ?? [])->contains(function ($item) use ($keyword) {
                    return str_contains($item['name'], $keyword);
                });
            });
        }

        if ($request->filled('date_from') || $request->filled('date_to')) {
            $from = $request->input('date_from');
            $to = $request->input('date_to');

            $history = array_filter($history, function ($entry) use ($from, $to) {
                $entryDate = $entry['date'] ?? null;

                if (!$entryDate) return false;

                if ($from && $entryDate < $from) return false;
                if ($to && $entryDate > $to) return false;

                return true;
            });
        }

        return view('shopping_history', [
            'history' => $history,
            'filters' => $request->only(['store', 'item_keyword', 'date_from', 'date_to']),
        ]);
    }
}