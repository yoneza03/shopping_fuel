@extends('layouts.app')

@section('title', '車種編集')

@section('content')
<div class="container">
  <h2 class="mb-4">🚙 車種情報の編集</h2>

  @if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
  @endif

  <form method="POST" action="{{ route('vehicle.update', $vehicle->id) }}">
    @csrf
    @method('PUT')

    <div class="row g-2 mb-3">
      <div class="col-md-4">
        <label class="form-label">車種名</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $vehicle->name) }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">メーカー</label>
        <input type="text" name="maker" class="form-control" value="{{ old('maker', $vehicle->maker) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">年式</label>
        <input type="number" name="year" class="form-control" value="{{ old('year', $vehicle->year) }}">
      </div>
    </div>

    <div class="text-end">
      <button type="submit" class="btn btn-warning">変更を保存</button>
      <a href="{{ route('vehicle.index') }}" class="btn btn-secondary ms-2">戻る</a>
    </div>
  </form>
</div>
@endsection