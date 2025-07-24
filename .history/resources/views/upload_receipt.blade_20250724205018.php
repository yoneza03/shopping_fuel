@extends('layouts.app')
@section('title', 'レシートアップロード')

@section('content')
    <h2>📸 レシート画像アップロード</h2>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <form method="POST" action="/upload-receipt" enctype="multipart/form-data">
        @csrf
        <input type="file" name="receipt_image" accept="image/*" capture="environment" required>
        <button type="submit" class="btn btn-primary mt-2">アップロード</button>    
    </form>

    @if(session('path'))
        <div class="mt-3">
            <p>アップロードした画像：</p>
            <img src="{{ asset('storage/' . session('path')) }}" alt="レシート画像" style="max-width: 100%; height: auto;">
        </div>
    @endif
@endsection