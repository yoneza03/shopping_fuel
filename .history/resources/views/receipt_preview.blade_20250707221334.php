@extends('layouts.app')

@section('title', 'レシート画像の確認')

@section('content')
<div class="container text-center">
  <h2 class="mb-4">🧾 撮影したレシート画像</h2>

  {{-- 画像表示 --}}
  <div class="mb-4">
    <img src="{{ asset($imagePath) }}" alt="レシート画像" class="img-fluid rounded border shadow">
  </div>

  {{-- アクションボタン --}}
  <form method="POST" action="{{ route('receipt.confirm') }}">
    @csrf
    <input type="hidden" name="image_path" value="{{ $imagePath }}">

    <div class="d-flex justify-content-center gap-3">
      <button type="submit" class="btn btn-success">この画像で登録する</button>
      <a href="{{ route('camera.start') }}" class="btn btn-outline-secondary">撮り直す</a>
    </div>
  </form>
</div>
@endsection
