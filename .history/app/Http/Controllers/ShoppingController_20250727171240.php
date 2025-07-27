<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingRecord;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use App\Utils\ImageProcessor;


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

            Log::debug('OCRに渡す実パス: ' . $fullPath);

            // 色反転（または他の前処理）
            $processedPath = $publicTmpPath . '/processed_' . $filename;
            ImageProcessor::negate($fullPath, $processedPath);

            $enhancedPath = $publicTmpPath . '/enhanced_' . $filename;
            ImageProcessor::enhance($fullPath, $enhancedPath);

            Log::debug('📸 Intervention画像読み込み開始');
            $image = Image::make($fullPath)
                ->resize(1200, null)         // 横解像度アップで文字の明瞭さ向上
                ->greyscale()                // モノクロ化
                ->contrast(10)               // 文字の輪郭強調
                ->brightness(5)              // 明るさ調整（調整値は要チューニング）
                ->sharpen(2);                // 輪郭補正

            Log::debug('📸 Intervention画像リサイズ開始');
            if ($image->width() < 1000) {
                Log::debug('📸 リサイズ完了 → OCR開始');

                $image->resize(1600, null, function ($constraint) {
                    $constraint->aspectRatio(); // 縦横比を維持して拡大
                });
                $image->save($fullPath); // 上書き保存
            }

            
            $ocrText = (new TesseractOCR($enhancedPath))
              ->lang('jpn')
              ->psm(6)     
              ->oem(1)     
              ->run();  
            Log::debug('📦 OCR結果: ' . $ocrText);
            
            //  OCR結果をパース（必要に応じて parseReceipt メソッドを用意）
            $parsed = $this->parseReceipt($ocrText);
            Log::debug('📦 パース後データ: ' . json_encode($parsed));
            
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

        $lines = explode("\n", $text);
        $excludeKeywords = config('receipt_keywords.exclude');
        $keywords = config('receipt_keywords.keywords');
        $storeList = config('receipt_keywords.stores');

        foreach ($lines as $line) {
            $line = trim($line);
            $lineClean = mb_convert_kana($line, 'as'); // 全角数字・英字→半角
            //  店舗名の検出
            if ($store === '') {
                foreach ($storeList as $pattern) {
                    if (mb_stripos($lineClean, $pattern) !== false) {
                        $store = $pattern;
                        break;
                    }
                }
            }

            //  除外ワードチェック（TELなど含む）
            $skip = false;
            foreach ($excludeKeywords as $word) {
                if (stripos($lineClean, $word) !== false) {
                    $skip = true;
                    break;
                }
            }
            if ($skip) continue;

            if ($date === '') {
                // 通常の形式（2025/07/25 等）
                if (preg_match('/(\d{4})\D{0,3}(\d{1,2})\D{0,3}(\d{1,2})/', $lineClean, $match)) {
                    $y = intval($match[1]);
                    $m = intval($match[2]);
                    $d = intval($match[3]);
                    Log::debug('🕒 日付候補（通常）: ' . $lineClean);

                    if ($y >= 2000 && $y <= 2100 && $m >= 1 && $m <= 12 && $d >= 1 && $d <= 31) {
                        $date = sprintf('%04d-%02d-%02d', $y, $m, $d);
                    }
                }
                // 日本語表記（2025年7月25日 等）
                elseif (preg_match('/(\d{4})年\s*(\d{1,2})月\s*(\d{1,2})日/u', $lineClean, $match)) {
                    $y = intval($match[1]);
                    $m = intval($match[2]);
                    $d = intval($match[3]);
                    Log::debug('🕒 日付候補（和式）: ' . $lineClean);

                    if ($y >= 2000 && $y <= 2100 && $m >= 1 && $m <= 12 && $d >= 1 && $d <= 31) {
                        $date = sprintf('%04d-%02d-%02d', $y, $m, $d);
                    }
                }
                elseif (preg_match('/年\s*(\d{1,2})月\s*(\d{1,2})日/u', $lineClean, $match)) {
                    $y = now()->year; // 現在の年を仮定
                    $m = intval($match[1]);
                    $d = intval($match[2]);
                    Log::debug('🕒 日付候補（年不明 → 補完）: ' . $lineClean);

                    if ($m >= 1 && $m <= 12 && $d >= 1 && $d <= 31) {
                        $date = sprintf('%04d-%02d-%02d', $y, $m, $d);
                    }
                }
                
            }
            //  商品行の基本フィルター
            if (strlen($lineClean) < 6) continue;
            if (!preg_match('/\d/', $lineClean)) continue;

            //  商品抽出
            if (preg_match('/^(.+?)\s{1,}[¥\\\]?\s*([\d\s,\.]+)[\*％%]?$/u', $lineClean, $match)) {
                $name = trim($match[1]);
                $priceRaw = str_replace([' ', ',', 'g'], '', $match[2]); // 誤認文字を除去
                $price = preg_replace('/[^0-9\.]/', '', $priceRaw); // 数字以外を除去
                $price = is_numeric($price) ? floatval($price) : null;

                $invalidWords = ['伝票', 'カード', '承認', '控え', '番号', 'VISA', '本人', 'IC', 'TEL', 'FAX', 'お客様', '売上票', '取引内容'];

                foreach ($invalidWords as $bad) {
                    if (stripos($name, $bad) !== false) {
                        continue 2; // 商品登録しない
                    }
                }

                if ($price && strlen($name) >= 4 && !preg_match('/^[¥\d\s\W]+$/u', $name)) {
                    Log::debug('📦 商品候補:', ['line' => $lineClean, 'name' => $name, 'price' => $price]);

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

        // price を整数化！
        foreach ($data['items'] as &$item) {
            if (isset($item['price'])) {
                $item['price'] = intval($item['price']); 
            }
        }
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

             Log::debug('🏬 店舗フィルター:', ['store' => $request->input('store')]);

            $history = $history->filter(function ($entry) use ($request) {

                Log::debug('🏪 比較中の店舗名:', ['entry_store' => $entry->store]);

                return str_contains($entry->store, $request->input('store'));
            });
        }

        if ($request->filled('item_keyword')) {
            $keyword = $request->input('item_keyword');

            Log::debug('🔍 商品キーワード:', ['keyword' => $keyword]);

            $history = $history->filter(function ($entry) use ($keyword) {
                $items = is_array($entry->items) ? $entry->items : json_decode($entry->items, true);

                Log::debug('📦 商品一覧（decode後）:', ['items' => $items]);

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

            Log::debug('📅 入力された日付フィルター:', ['from' => $from, 'to' => $to]);


            $history = $history->filter(function ($entry) use ($from, $to) {
                $entryDate = \Carbon\Carbon::parse($entry->date)->format('Y-m-d');

                Log::debug('📜 レコード日付:', ['entry_date' => $entryDate]);

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
    //CSV出力
    public function export(): StreamedResponse
    {
        $records = ShoppingRecord::orderBy('date', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="shopping_records.csv"',
        ];

        return response()->stream(function () use ($records) {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, ['店舗名', '購入日', '商品名', 'カテゴリー', '価格']);

            foreach ($records as $record) {
                $items = is_array($record->items) ? $record->items : json_decode($record->items, true);
                foreach ($items as $item) {
                    fputcsv($stream, [
                        $record->store,
                        $record->date,
                        $item['name'] ?? '',
                        $item['category'] ?? '', 
                        isset($item['price']) ? '¥' . number_format($item['price'], 0) : '',
                    ]);
                }
            }

            fclose($stream);
        }, 200, $headers);
    }

}