<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShoppingController extends Controller
{
    // 買い物データ入力ページ
    public function entry()
    {
        $data = session()->get('shopping');
        // dd($data);
        return view('shopping_entry', compact('data'));
    }


    // 確認画面
    public function confirm(Request $request)
    {
        // 入力内容をセッションに保存
        $data = $request->except('receipt');

        session()->put('shopping', $data);
        return view('shopping_confirm',['data' => $request->all()]);
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