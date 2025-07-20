@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<h1 class="mb-4">買物サポートと燃費計算</h1>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="login-card"> 
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
                        <a href="{{ route('password.reset') }}" class="btn btn-outline-warning w-100">パスワードを忘れた方</a>
                    </div>

                    <!-- 新規登録 -->
                    <div class="mb-4">
                        <a href="{{ route('register') }}" class="btn btn-outline-success w-100">新規登録</a>
                    </div>

                    <!-- ログインボタン -->
                    <button type="submit" class="btn btn-primary w-100">ログイン</button>
                </form>
            </div>

        </div>
    </div>
@endsection
