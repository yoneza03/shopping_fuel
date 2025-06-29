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

        // 新しいデータを追加（先頭に入れる）
        array_unshift($history, $newData);

        // 更新
        session()->put('shopping_history', $history);

        // 入力中データを破棄
        session()->forget('shopping');

        return redirect()->route('shopping.entry')->with('success', '登録完了しました');
    }
    
    // 買い物履歴
    public function history()
    {
        // TODO: DBから取得して渡す
        return view('shopping_history');
    }
}