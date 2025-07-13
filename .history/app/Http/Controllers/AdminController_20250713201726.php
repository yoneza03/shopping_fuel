<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    //ユーザー一覧を取得してビューに渡す
    public function index()
    {
        $users = User::orderBy('id')->get();
        return view('admin.dashboard', compact('users'));
    }

    //編集画面へ遷移
    public function edit($id)
    {
        if (auth()->user()->role !== 'edit') {
            abort(403); // 権限なしエラー
        }
        $user = User::findOrFail($id);
        return view('user_permissions', compact('user'));
    }

    //権限更新
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'edit') {
            abort(403); // 権限なしエラー
        }
        $request->validate([
            'role' => 'required|in:view,edit'
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin')->with('success', '権限を更新しました！');
    }

    // 削除処理
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin')->with('success', 'ユーザーを削除しました！');
    }
}


