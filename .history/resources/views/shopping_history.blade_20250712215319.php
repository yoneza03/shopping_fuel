@extends('layouts.app')

@section('title', '買い物履歴')

@section('content')
<div class="container">
  <h2 class="mb-4">買い物履歴</h2>

  <form action="{{ route('price.history.jump') }}" method="GET" class="row g-2 mb-4">
    <div class="col-auto">
      <input type="text" name="item" class="form-control" placeholder="商品名を入力（例：チョコ）">
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-outline-primary">価格変動を確認する</button>
    </div>
  </form>

  <form action="{{ route('shopping.history') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-3">
      <div class="col-md-3">
      <input type="text" name="store" class="form-control" placeholder="店舗名で検索"
        value="{{ $filters['store'] ?? '' }}">
      </div>

      <div class="col-md-3">
      <input type="text" name="item_keyword" class="form-control" placeholder="商品名で検索"
        value="{{ $filters['item_keyword'] ?? '' }}">
      </div>

      <div class="col-md-3">
      <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
      </div>

      <div class="col-md-3">
      <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
      </div>

      <div class="col-md-12 d-flex mt-2">
      <button type="submit" class="btn btn-primary me-2">検索</button>
      <a href="{{ route('shopping.history') }}" class="btn btn-outline-secondary">リセット</a>
      </div>
    </div>
  </form>

  @if ($history->isEmpty())
  <p>まだ登録されたデータはありません。</p>
  @else
  @foreach ($history as $record)
    @php
      $items = is_array($record->items) ? $record->items : json_decode($record->items, true);
    @endphp
    <div class="card mb-3">
      <div class="card-header">
        {{ $record->store }}（{{ $record->date }}）
      </div>
      <ul class="list-group list-group-flush">
        @foreach ($items ?? [] as $item)
          <li class="list-group-item">
            {{ $item['name'] }} - ¥{{ number_format($item['price']) }}
          </li>
        @endforeach
      </ul>
    </div>
  @endforeach
  @endif
</div>
<div class="mb-3 text-end">
  <a href="{{ route('shopping.export') }}" class="btn btn-outline-primary">
    CSVで出力する
  </a>
</div>
@endsection