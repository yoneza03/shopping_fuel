@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <div class="row">
    <!-- 左：履歴テーブル -->
    <div class="col-md-6">
      <h4>🚗 燃費履歴一覧</h4>
      @if ($records->count())
        <table class="table table-bordered">
          <thead><tr><th>日付</th><th>距離</th><th>給油量</th><th>燃費</th></tr></thead>
          <tbody>
            @foreach ($records as $r)
              <tr>
                <td>{{ $r->created_at->format('Y-m-d') }}</td>
                <td>{{ $r->distance }} km</td>
                <td>{{ $r->fuel_amount }} L</td>
                <td>{{ number_format($r->fuel_efficiency, 2) }} km/L</td>
              </tr>
            @endforeach
          </tbody>
        </table>
        
        <div class="d-flex justify-content-between mt-3">
          <a href="{{ route('vehicle.report') }}" class="btn btn-outline-secondary">
            車種別レポートを見る
          </a>
          <a href="{{ route('fuel.export') }}" class="btn btn-outline-primary">
            CSV出力
          </a>
        </div>

      @else
        <p>データがありません。</p>
      @endif
    </div>
   <!-- 右：グラフ -->
    <div class="col-md-6">
      <h4>📈 燃費グラフ</h4>
      <canvas id="fuelChart" height="200"></canvas>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const labels = @json($labels);
  const data = @json($efficiencies);

  new Chart(document.getElementById('fuelChart'), {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: '燃費 (km/L)',
        data: data,
        borderColor: 'rgba(255, 99, 132, 1)',
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        tension: 0.4,
        pointRadius: 4
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: '燃費の推移'
        }
      }
    }
  });
});
</script>
@endsection