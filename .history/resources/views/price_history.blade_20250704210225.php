
@extends('layouts.app')

@section('title', '価格変動履歴')

<h2>{{ $itemName }} の価格変動履歴</h2>
<table class="table table-bordered mt-3">
  <thead>
    <tr>
      <th>購入日</th>
      <th>価格</th>
      <th>店舗</th>
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