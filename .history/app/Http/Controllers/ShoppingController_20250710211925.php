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

            // 🔥 再度OCR結果をセット
            $data['ocrText'] = $ocrText;
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
        $excludeKeywords = config('receipt_keywords.exclude');
        $keywords = config('receipt_keywords.keywords');
        $storeList = config('receipt_keywords.stores');
        

        foreach ($lines as $line) {
            $line = trim($line);
            $lineClean = mb_convert_kana($line, 'as'); // 全角数字・英字→半角

            // 店舗名を検出
        $storePatterns = config('receipt_keywords.stores');

        if ($store === '') {
            foreach ($storePatterns as $pattern) {
                if (mb_stripos($lineClean, $pattern) !== false) {
                $store = $pattern;
                break;
                }
            }
        }  
        if (stripos($lineClean, 'TEL') !== false || stripos($lineClean, 'FAX') !== false) {
            continue; // 電話番号・FAX行はスキップして日付判定しない
        }
          // 年/月/日または 年月日（スペース・全角対応）
        if ($date === '' && preg_match('/(\d{4})\D{0,2}(\d{1,2})\D{0,2}(\d{1,2})/', $lineClean, $match)) {
            $year  = preg_replace('/\s+/', '', $match[1]);
            $month = preg_replace('/\s+/', '', $match[2]);
            $day   = preg_replace('/\s+/', '', $match[3]);

            $date = str_pad($year, 4, '0', STR_PAD_LEFT) . '-' .
                    str_pad($month, 2, '0', STR_PAD_LEFT) . '-' .
                    str_pad($day, 2, '0', STR_PAD_LEFT);
        } 
        
        // 除外ワードチェック → 商品抽出対象か判定
            $skip = false;
            foreach ($excludeKeywords as $word) {
                if (stripos($lineClean, $word) !== false) {
                    $skip = true;
                    break;
                }
            }
            if ($skip) continue;
            if (strlen($lineClean) < 6) continue;
            if (!preg_match('/\d/', $lineClean)) continue;

            // キーワードマッチ（任意）
            foreach ($keywords as $word) {
                if (mb_strpos($lineClean, $word) !== false) {
                    break; // 特定の商品ワードがあれば補助的に保持（なくてもOK）
                }
            }
            
            // 品名＋金額
            if (preg_match('/^(.+?)\s{1,}[¥\\\]?\s*([\d\s,\.]+)[\*％%]?$/u', $lineClean, $match)) {
                $name = trim($match[1]);
                $priceRaw = str_replace([' ', ','], '', $match[2]);
                $price = is_numeric($priceRaw) ? floatval($priceRaw) : null;

                if ($price && strlen($name) > 3 && !preg_match('/^[¥\d\s\W]+$/u', $name)) {
                    $items[] = [
                        'name' => $name,
                        'price' => $price
                    ];
                }
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
            'items' => $data['items'],
        ]);
        
        if (empty($data['date'])) {
            return redirect()->route('shopping.confirm.view')
                ->with('error', '日付が未入力のため登録できませんでした。もう一度ご確認ください。');
        }
        session()->forget('shopping');
        return redirect()->route('shopping.entry')->with('success', '登録完了しました');
    }

    // 買い物履歴
    public function history(Request $request)
    {
        $history = ShoppingRecord::orderBy('date', 'desc')->get();

        // 検索条件がある場合はフィルタリング
        if ($request->filled('store')) {

             \Log::debug('🏬 店舗フィルター:', ['store' => $request->input('store')]);

            $history = $history->filter(function ($entry) use ($request) {

                \Log::debug('🏪 比較中の店舗名:', ['entry_store' => $entry->store]);

                return str_contains($entry->store, $request->input('store'));
            });
        }

        if ($request->filled('item_keyword')) {
            $keyword = $request->input('item_keyword');

            \Log::debug('🔍 商品キーワード:', ['keyword' => $keyword]);

            $history = $history->filter(function ($entry) use ($keyword) {
                $items = is_array($entry->items) ? $entry->items : json_decode($entry->items, true);

                \Log::debug('📦 商品一覧（decode後）:', ['items' => $items]);

                return collect($items)->contains(function ($item) use ($keyword) {
                    return 
                    (isset($item['name']) && str_contains($item['name'], $keyword)) ||
                    (isset($item['category']) && str_contains($item['category'], $keyword));
                });      
            });
        }

        if ($request->filled('date_from') || $request->filled('date_to')) {
            $from = $request->input('date_from');
            $to = $request->input('date_to');

            \Log::debug('📅 入力された日付フィルター:', ['from' => $from, 'to' => $to]);


            $history = $history->filter(function ($entry) use ($from, $to) {
                $entryDate = \Carbon\Carbon::parse($entry->date)->format('Y-m-d');

                \Log::debug('📜 レコード日付:', ['entry_date' => $entryDate]);

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