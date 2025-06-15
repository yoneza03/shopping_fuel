@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="border rounded p-4 shadow text-center" style="width: 400px;">
        <h2 class="mb-4">パスワード再設定</h2>
        
        <form action="{{ route('password.reset.send') }}" method="POST">
            @csrf
            <input type="email" name="email" class="form-control mb-3" placeholder="メールアドレス" required>
            <button type="submit" class="btn btn-primary w-100">メール送信</button>
        </form>
    </div>
</div>
@endsection