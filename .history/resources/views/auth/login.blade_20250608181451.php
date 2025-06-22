<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    @extends('layouts.app')

    @section('content')
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="border rounded p-4 shadow text-center" style="width: 400px;">

            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif        
            
            <h2 class="mb-4">ログイン</h2>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <!-- メールアドレスフォーム -->
                <div class="mb-3">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <!-- パスワードフォーム -->
                <div class="mb-3">
                    <label for="password" class="form-label">パスワード</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <!-- パスワードリセット -->
                <div class="mb-3">
                    <a href="{{ route('password.reset') }}" class="btn btn-outline-primary w-100">パスワードを忘れた方</a>
                </div>

                <!-- 新規登録 -->
                <div class="mb-4">
                    <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">新規登録</a>
                </div>

                <!-- ログインボタン -->
                <button type="submit" class="btn btn-primary w-100">ログイン</button>
            </form>
        </div>
    </div>
    @endsection
</body>
</html>