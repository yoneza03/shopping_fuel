<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        session()->put('shopping', $data); 
        session()->save();

        \Log::info('セッションに保存', ['data' => $data]);

        //  セッションの代わりに flash data（1リクエスト限定）を with() で送る
        return redirect()->route('shopping.confirm.view');
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
        $newData = session()->get('shopping');

        // 現在の履歴を取得（なければ空配列）
        $history = session()->get('shopping_history', []);

        array_unshift($history, $newData);
        session()->put('shopping_history', $history);
        session()->forget('shopping');

        return redirect()->route('shopping.entry')->with('success', '登録完了しました');
    }

    // 買い物履歴
    public function history(Request $request)
    {
        $history = session('shopping_history', []);

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

        if ($request->filled('date')) {
            $history = array_filter($history, function ($entry) use ($request) {
                return $entry['date'] === $request->input('date');
            });
        }

        return view('shopping_history', [
            'history' => $history,
            'filters' => $request->only(['store', 'date']),
        ]);
    }
}