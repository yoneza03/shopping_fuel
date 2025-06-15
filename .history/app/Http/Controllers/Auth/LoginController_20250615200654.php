<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        return redirect()->route('home'); // ログイン成功時、ホーム画面へ遷移
    }

    // ログイン失敗時にエラーページへ遷移
    return response()->view('error_page', [
        'message' => 'ログイン情報が正しくありません。',
        'back_url' => route('login')
    ], 403);
    }

    public function logout(Request $request)
    {
        if (!auth()->check()) {
        dd("ユーザーがログインしていません"); // デバッグ用
    }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}