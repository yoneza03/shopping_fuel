@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="register-card"> 
        <div class="border rounded p-4 shadow text-center" style="width: 400px;">
            <h2 class="mb-4">新規登録</h2>

            <!-- 新規登録フォーム -->
            <form action="{{ route('register.confirm') }}" method="POST">
                @csrf

                <!-- ユーザー名 -->
                <div class="mb-3">
                    <label for="name" class="form-label">ユーザー名</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <!-- メールアドレス -->
                <div class="mb-3">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <!-- パスワード入力 -->
                <div class="mb-3">
                    <label for="password" class="form-label">パスワード</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <!-- パスワード確認入力 -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">パスワード確認</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <!-- 確認ボタン -->
                <button type="submit" class="btn btn-primary w-100">入力確認</button>
            </form>
        </div>
    </div>
</div>
@endsection
