@extends('layouts.app')

@section('title', '車種別燃費レポート')

@section('content')
<div class="container">
  <h2 class="mb-4">🚗 車種別燃費レポート</h2>

  @foreach ($vehicles as $vehicle)
    <div class="card mb-4">
      <div class="card-header bg-info text-white">
        {{ $vehicle->name }}（{{ $vehicle->maker ?? 'メーカー不明' }}）
      </div>
      <div class="card-body">
        <p><strong>登録年式：</strong> {{ $vehicle->year ?? '—' }}</p>
        <p><strong>平均燃費：</strong>
          @php
            $records = $vehicle->fuelRecords;
            $average = $records->avg('fuel_efficiency');
          @endphp
          {{ $records->count() ? round($average, 2) . ' km/L' : '記録なし' }}
        </p>
        <a href="{{ route('fuel.history', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-outline-primary">
          詳細履歴・グラフへ
        </a>
      </div>
    </div>
  @endforeach
</div>
@endsection