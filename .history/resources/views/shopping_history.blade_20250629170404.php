@extends('layouts.app')

@section('title', '買い物履歴')

@section('content')
<div class="container">
  <h2 class="mb-4">買い物履歴</h2>

  @php
    $history = session('shopping_history', []);
  @endphp
  
    <form action="{{ route('shopping.history') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-3">
        <div class="col-md-4">
        <input type="text" name="store" class="form-control" placeholder="店舗名で検索"
            value="{{ $filters['store'] ?? '' }}">
        </div>
        <div class="col-md-4">
        <input type="date" name="date" class="form-control"
            value="{{ $filters['date'] ?? '' }}">
        </div>
        <div class="col-md-4 d-flex">
        <button type="submit" class="btn btn-primary me-2">検索</button>
        <a href="{{ route('shopping.history') }}" class="btn btn-outline-secondary">リセット</a>
        </div>
    </div>
    </form>

  @if (count($history) === 0)
    <p>まだ登録されたデータはありません。</p>
  @else
    @foreach ($history as $index => $data)
      <div class="card mb-3">
        <div class="card-header">
          {{ $data['store'] ?? '不明な店舗' }}（{{ $data['date'] ?? '不明な日付' }}）
        </div>
        <ul class="list-group list-group-flush">
          @foreach ($data['items'] ?? [] as $item)
            <li class="list-group-item">
              {{ $item['name'] }} - ¥{{ number_format($item['price']) }}
            </li>
          @endforeach
        </ul>
      </div>
    @endforeach
  @endif
</div>
@endsection