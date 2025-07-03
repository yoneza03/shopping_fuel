<h2>{{ $itemName }} の価格変動履歴</h2>
<table class="table table-bordered mt-3">
  <thead>
    <tr><th>購入日</th><th>価格</th><th>店舗</th></tr>
  </thead>
  <tbody>
    @foreach ($history as $record)
      <tr>
        <td>{{ $record->date }}</td>
        <td>¥{{ number_format($record->price) }}</td>
        <td>{{ $record->store }}</td>
      </tr>
    @endforeach
  </tbody>
</table>