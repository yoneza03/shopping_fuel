<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShoppingController extends Controller
{
    // 買い物データ入力ページ
    public function entry()
    {
        return view('shopping_entry');
    }

    // 確認画面（セッション受取想定）
    public function confirm(Request $request)
    {
        // 入力内容をセッションに保存（仮）
        session()->put('shopping', $request->all());
        return view('shopping_confirm');
    }

    // データ登録処理
    public function store()
    {
        // session()->get('shopping') から保存処理
        // TODO: 実装

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