
@extends('layouts.app')
@section('title', '買い物データ確認')

@section('content')
<div class="container">
  <h2 class="mb-4">登録内容の確認</h2>
  @if (!empty($data['ocrText']))
    <div class="mb-3">
      <label for="ocrText" class="form-label">OCR読み取り結果</label>
      <textarea name="ocrText" class="form-control" rows="6">{{ $data['ocrText'] }}</textarea>
    </div>
  @endif
  <div class="mb-3">
    <strong>店舗名：</strong> {{ $data['store'] ?? '' }}<br>
    <strong>購入日：</strong> {{ $data['date'] ?? '' }}
  </div>

  <table class="table table-striped">
    <thead>
      <tr><th>商品名</th><th>価格</th></tr>
    </thead>
    <tbody>
      @foreach ($data['items'] ?? [] as $item)
        <tr>
          <td>{{ $item['name'] }}</td>
          <td>¥{{ number_format($item['price']) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  @if (!empty($data['receipt']))
    <div class="mb-3">
      <strong>添付ファイル：</strong> {{ $data['receipt']->getClientOriginalName() }}
    </div>
  @endif

  <div class="d-flex justify-content-between mt-4">
    <form action="{{ route('shopping.store') }}" method="POST">
      @csrf
      <textarea name="ocrText" style="display:none;">{{ $ocrText }}</textarea>
      <button type="submit" class="btn btn-success">登録</button>
    </form>

    <form action="{{ route('shopping.entry') }}" method="GET">
        <button type="submit" class="btn btn-secondary">修正</button>
    </form>

    <form action="{{ route('shopping.clear') }}" method="GET" onsubmit="return confirm('本当に削除しますか？');">
      <button type="submit" class="btn btn-danger">削除</button>
    </form>
  </div>
</div>
@endsection