@extends('layouts.app')

@section('content')

{{-- <form method="POST" action="{{ route('fuel.store') }}"> --}}
<form action="{{ route('fuel.store') }}" method="POST">
  @csrf

  <div class="mb-3">
    <label for="date" class="form-label">日付</label>
    <input type="date" name="date" class="form-control" required>
  </div>

  <div class="mb-3 row">
    <div class="col">
      <label class="form-label">走行距離（km）</label>
      <input type="number" name="distance" class="form-control" step="any" id="distance" required>
    </div>
    
    <div class="col">
      <label class="form-label">給油量（L）</label>
      <input type="number" name="fuel_amount" class="form-control" step="any" id="fuel_amount" required>
    </div>

    <div class="col d-flex align-items-end">
      <button type="button" class="btn btn-outline-primary" id="calc-btn">計算</button>
    </div>
  </div>

  <div class="mb-3">
    <label class="form-label">燃費</label>
    <input type="text" class="form-control" id="result" readonly>
  </div>

  <select name="vehicle_id" class="form-select" required>
    @foreach ($vehicles as $vehicle)
      <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
    @endforeach
  </select>

  <button type="submit" class="btn btn-success">保存</button>
</form>
@endsection

@section('scripts')
<script>
document.getElementById('calc-btn').addEventListener('click', () => {
  const dist = parseFloat(document.getElementById('distance').value);
  const fuel = parseFloat(document.getElementById('fuel_amount').value);
  const resultEl = document.getElementById('result');

  if (!isNaN(dist) && !isNaN(fuel) && fuel !== 0) {
    const mileage = (dist / fuel).toFixed(2);
    resultEl.value = `${mileage} km/L`;
  } else {
    resultEl.value = '計算できません';
  }
});
</script>
@endsection