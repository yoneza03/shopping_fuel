
@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <h2 class="mb-4">
    <i class="bi bi-graph-up-arrow me-2"></i> {{ $itemName }} の価格変動履歴
  </h2>

  @if (count($history))
  <!-- 📊 統計ブロック（テーブル外）-->
    @if(isset($summary))
    <div class="alert alert-info">
      <ul class="mb-0">
        <li><strong>平均価格:</strong> ¥{{ number_format($summary['avg'], 2) }}</li>
        <li><strong>最安値:</strong> ¥{{ $summary['min'] }}（{{ $summary['min_date'] }}）</li>
        <li><strong>最高値:</strong> ¥{{ $summary['max'] }}（{{ $summary['max_date'] }}）</li>
        <li><strong>購入回数:</strong> {{ $summary['count'] }} 回</li>
      </ul>
    </div>
    @endif

    <div class="mb-5">
      <h4>📈 価格変動グラフ</h4>
      <canvas id="priceChart" height="100"></canvas>
    </div>

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

@section('scripts')
  <!-- comparison.js の読み込み -->
  <script src="{{ asset('js/comparison.js') }}"></script>

  <!-- グラフ描画の呼び出し -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const labels = @json($labels);
      const prices = @json($prices);
      const itemName = @json($itemName);

      renderPriceChart(labels, prices, itemName);
    });
  </script>
@endsection