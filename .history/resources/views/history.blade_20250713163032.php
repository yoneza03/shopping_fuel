@extends('layouts.app')
@section('title', '履歴一覧')

@section('content')

<form action="{{ route('history.search') }}" method="GET" class="mb-4">
  <div class="card p-3">
    <h5>🔍 履歴検索バー</h5>

    {{-- 履歴種別選択 --}}
    <div class="mb-2">
      <label class="form-label">表示する履歴：</label>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="type[]" value="shopping" checked>
        <label class="form-check-label">買い物履歴</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="type[]" value="price" >
        <label class="form-check-label">価格変動履歴</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="type[]" value="fuel" >
        <label class="form-check-label">燃費履歴</label>
      </div>
    </div>

    {{-- 絞り込み項目 --}}
    <div class="row g-2">
      <div class="col-md-4">
        <label class="form-label">品名</label>
        <input type="text" name="item_keyword" class="form-control" placeholder="例：牛乳">
      </div>
      <div class="col-md-4">
        <label class="form-label">価格（以上）</label>
        <input type="number" name="price_min" class="form-control" placeholder="例：100">
      </div>
      <div class="col-md-4">
        <label class="form-label">店舗</label>
        <input type="text" name="store" class="form-control" placeholder="例：イオン">
      </div>
      <div class="col-md-6">
        <label class="form-label">日付（開始）</label>
        <input type="date" name="date_from" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">日付（終了）</label>
        <input type="date" name="date_to" class="form-control">
      </div>
    </div>

    {{-- 検索ボタン --}}
    <div class="text-end mt-3">
      <button type="submit" class="btn btn-primary">検索する</button>
    </div>
  </div>
</form>

{{-- 買い物履歴 --}}
@if(!empty($results['shopping']))
  <h4>🛍 買い物履歴</h4>
  <table class="table table-bordered">
    <thead><tr><th>店舗</th><th>購入日</th><th>商品名</th><th>価格</th></tr></thead>
    <tbody>
      @foreach ($results['shopping'] as $row)
        <tr>
          <td>{{ $row['store'] }}</td>
          <td>{{ $row['date'] }}</td>
          <td>{{ $row['name'] }}</td>
          <td>¥{{ number_format($row['price']) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endif

{{-- 価格変動履歴 --}}
@if(!empty($results['price']))
  <h4>📊 価格変動履歴</h4>
  <table class="table table-bordered">
    <thead><tr><th>店舗</th><th>購入日</th><th>商品名</th><th>価格</th></tr></thead>
    <tbody>
      @foreach ($results['price'] as $row)
        <tr>
          <td>{{ $row['store'] }}</td>
          <td>{{ $row['date'] }}</td>
          <td>{{ $row['name'] }}</td>
          <td>¥{{ number_format($row['price']) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endif

{{-- 燃費履歴 --}}
@if(!empty($results['fuel']))
  <h4>⛽ 燃費履歴</h4>
  <table class="table table-bordered">
    <thead><tr><th>給油日</th><th>距離</th><th>給油量</th><th>燃費</th></tr></thead>
    <tbody>
      @foreach ($results['fuel'] as $row)
        <tr>
          <td>{{ $row['date'] }}</td>
          <td>{{ $row['distance'] }} km</td>
          <td>{{ $row['fuel_amount'] }} L</td>
          <td>{{ $row['efficiency'] }} km/L</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endif
@endsection