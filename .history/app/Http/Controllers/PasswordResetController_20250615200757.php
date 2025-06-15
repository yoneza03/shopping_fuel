<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class PasswordResetController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.password_reset_request');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // 🔹 トークンを生成
        $token = Password::createToken(User::where('email', $request->email)->first());

        // 🔹 パスワードリセットリンクを作成
        $resetUrl = route('password.reset.form', ['token' => $token, 'email' => $request->email]);

        // 🔹 メール送信（Mailファサードを利用）
        \Mail::send('auth.password_reset_email', ['resetUrl' => $resetUrl], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('パスワードリセットリンク');
        });        
        return back()->with('success', 'パスワードリセットリンクを送信しました！');
    }
    
    public function showResetForm($token)
    {
        return view('auth.password_reset', compact('token'));
    }

 public function resetPassword(Request $request)
    {
    $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return back()->withErrors(['email' => 'ユーザーが見つかりません']);
    }

    $user->update([
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('login')->with('success', 'パスワード再設定完了しました！');
    }
}
