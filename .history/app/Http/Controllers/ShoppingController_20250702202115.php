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
            $uploaded = $request->file('receipt');
            $filename = uniqid() . '.' . $uploaded->getClientOriginalExtension();
            $publicTmpPath = public_path('tmp');

            if (!file_exists($publicTmpPath)) {
                mkdir($publicTmpPath, 0775, true);
            }

            $uploaded->move($publicTmpPath, $filename);
            $fullPath = $publicTmpPath . '/' . $filename;

            \Log::debug('OCRに渡す実パス: ' . $fullPath);

            $ocrText = (new TesseractOCR($fullPath))
                        ->lang('jpn+eng')
                        ->run();  
                        \Log::debug('📦 OCR結果: ' . $ocrText);
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
        $excludeKeywords = ['TEL', 'FAX', '登録番号', '会員番号', 'カード会社', '承認番号', '伝票番号', 'お取扱日', 'AID', 'VISA', '合計', '税', '本人確認'];

        $lineClean = trim($line);
        $skip = false;
        foreach ($excludeKeywords as $word) {
            if (stripos($lineClean, $word) !== false) {
                $skip = true;
                break;
            }
        }

        if (!$skip && preg_match('/(.+?)\s+[¥\\\]?\d{2,5}[\*％%]*/u', $line, $match)) {
            $items[] = [
                'name' => trim($match[1]),
                'price' => preg_replace('/[^\d]/', '', $match[0]), // 記号削除
            ];
        }

        foreach ($lines as $line) {
            $line = trim($line);

            // 店舗名を検出
            if ($store === '' && preg_match('/(イオン|トライアル|業務スーパー|ラ・ムー|サンディ)/u', $line, $match)) {
                $store = $match[1];
            }

            // 日付: 2025/ 5/10(土) → 数字のスラッシュ区切り＋任意の文字列
            if ($date === '' && preg_match('/\d{4}\s*\/\s*\d{1,2}\s*\/\s*\d{1,2}/', $line, $match)) {
                $date = preg_replace('/\s+/', '', $match[0]); // スペース除去
                $date = str_replace('/', '-', $date);
            }

            // 品名＋金額: 金額のあとに「円」なし、「*」つきも対応
            if (preg_match('/(.+?)\s+\\\?(\d{2,5})\*?$/u', $line, $match)) {
                $items[] = [
                    'name' => trim($match[1]),
                    'price' => $match[2],
                ];
            }
        }
        
        $keywords = ['弁当', 'パイ', 'ポテト', 'パン', '卵', '牛乳', '野菜', '肉', '水', 'おにぎり', 'アイス'];
        $matchKeyword = false;
        foreach ($keywords as $word) {
            if (mb_strpos($line, $word) !== false) {
                $matchKeyword = true;
                break;
            }
        }

        if ($matchKeyword && preg_match('/(.+?)\s+[¥\\\]?\d{2,5}/u', $line, $match)) {
            // 本当に商品らしい行なら追加
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