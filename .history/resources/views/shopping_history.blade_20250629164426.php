@extends('layouts.app')

@section('title', '買い物履歴')

@section('content')
<div class="container">
  <h2 class="mb-4">買い物履歴</h2>

  @php
    $history = session('shopping_history', []);
  @endphp

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