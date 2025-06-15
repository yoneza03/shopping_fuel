
@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="border rounded p-4 shadow text-center" style="width: 400px;">
        <h2 class="mb-4">パスワード再設定</h2>

        <form action="{{ route('password.reset.complete', ['token' => $token]) }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ request('email') }}">
            <input type="password" name="password" class="form-control mb-3" placeholder="新しいパスワード" required>
            <input type="password" name="password_confirmation" class="form-control mb-3" placeholder="新しいパスワード確認" required>
            <button type="submit" class="btn btn-primary w-100">登録</button>
        </form> 
    </div>
</div>
@endsection
