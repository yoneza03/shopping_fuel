@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="border rounded p-4 shadow text-center" style="width: 400px;">
        <h2 class="mb-4">新規登録　確認</h2>

            <p><strong>ユーザー名：</strong> {{ $name }}</p>
            <p><strong>メールアドレス：</strong> {{ $email }}</p>
            
            <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <!-- 登録ボタン -->
            <input type="hidden" name="name" value="{{ $name }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="password" value="{{ $password }}">
            <input type="hidden" name="password_confirmation" value="{{ $password }}">
            <button type="submit" class="btn btn-primary w-100">登録</button>
        </form>
    </div>
</div>
@endsection
