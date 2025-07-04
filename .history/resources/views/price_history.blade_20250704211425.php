
@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <h2 class="mb-4">
    <i class="bi bi-graph-up-arrow me-2"></i> {{ $itemName }} の価格変動履歴
  </h2>

  @if (count($history))
    <table class="table table-striped table-bordered">
      <thead class="table-light">
        <tr>
          <th scope="col">購入日</th>
          <th scope="col">価格</th>
          <th scope="col">店舗</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($history as $row)
          <tr>
            <td>{{ $row['date'] }}</td>
            <td>¥{{ number_format($row['price']) }}</td>
            <td>{{ $row['store'] }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <div class="alert alert-warning">
      履歴データが見つかりませんでした。
    </div>
  @endif

  <a href="{{ route('shopping.history') }}" class="btn btn-outline-secondary mt-3">
    ← 買い物履歴に戻る
  </a>
</div>
@endsection