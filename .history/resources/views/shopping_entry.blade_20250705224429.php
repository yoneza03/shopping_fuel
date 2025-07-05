
@extends('layouts.app')

@section('title', '買い物データ入力')

@section('content')
<div class="container">
  <h2 class="mb-4">買い物データ入力</h2>
  <form action="{{ route('shopping.confirm') }}" method="POST" enctype="multipart/form-data" id="shopping-form">
    @csrf
    @if (session('message'))
      <div class="alert alert-info">
        {{ session('message') }}
      </div>
    @endif
    <!-- 店舗名・購入日 -->
    <div class="row mb-3">
      <div class="col-md-6">
        <label for="store" class="form-label">店舗名</label>
        <input type="text" name="store" id="store" class="form-control"
          {{-- value="{{ old('store', $data['store'] ?? '') }}" required> --}}
          value="{{ old('store', $data['store'] ?? '') }}" >
      </div>

      <div class="col-md-6">
        <label for="date" class="form-label">購入日</label>
        <input type="date" name="date" id="date" class="form-control"
          {{-- value="{{ old('date', $data['date'] ?? '') }}" required> --}}
          value="{{ old('date', $data['date'] ?? '') }}" >
      </div>
    </div>

    <!-- 商品入力（テーブル） -->
    <table class="table table-bordered" id="item-table">
      <thead>
        <tr>
          <th>商品名</th>
          <th>価格</th>
          <th>カテゴリ</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      @php $items = $data['items'] ?? [['name' => '', 'price' => '']]; @endphp
      @foreach ($items as $index => $item)
        <tr>
          <td>
            <input type="text" name="items[{{ $index }}][name]" class="form-control"
              value="{{ old("items.$index.name", $item['name'] ?? '') }}" >
          </td>
          <td>
            <input type="number" name="items[{{ $index }}][price]" class="form-control"
              value="{{ old("items.$index.price", $item['price'] ?? '') }}" >
          </td>
          <td>
              <select name="items[{{ $index }}][category]" class="form-select">
                @php
                  $categories = ['お米', '乳製品', 'お菓子', 'パン', '肉', '魚', '野菜', 'その他'];
                  $selected = old("items.$index.category", $item['category'] ?? '');
                @endphp
                <option value="">-- 選択 --</option>
                @foreach ($categories as $category)
                  <option value="{{ $category }}" @selected($selected === $category)>
                    {{ $category }}
                  </option>
                @endforeach
              </select>
            </td>
          <td>
            <button type="button" class="btn btn-danger btn-sm remove-row">削除</button>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
    <button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="add-row">＋ 商品を追加</button>

    <!-- ファイル添付 -->
    <div class="mb-3">
      <label for="receipt" class="form-label">レシート画像またはPDF</label>
      <input type="file" name="receipt" id="receipt" class="form-control" accept=".jpg,.png,.pdf">
    </div>

    <!-- 確認ボタン -->
    <div class="text-end">
      <button type="submit" class="btn btn-primary">確認</button>
      <a href="{{ route('shopping.history') }}" class="btn btn-outline-info">履歴</a>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script>
let rowCount = {{ count($data['items'] ?? [1]) }};
document.getElementById('add-row').addEventListener('click', () => {
  const table = document.querySelector('#item-table tbody');
  const newRow = document.createElement('tr');

  newRow.innerHTML = `
    <td><input type="text" name="items[${rowCount}][name]" class="form-control" required></td>
    <td><input type="number" name="items[${rowCount}][price]" class="form-control" required></td>
    <td>
      <select name="items[${rowCount}][category]" class="form-select" required>
        <option value="">-- 選択 --</option>
        <option value="お米">お米</option>
        <option value="乳製品">乳製品</option>
        <option value="お菓子">お菓子</option>
        <option value="パン">パン</option>
        <option value="肉">肉</option>
        <option value="魚">魚</option>
        <option value="野菜">野菜</option>
        <option value="その他">その他</option>
      </select>
    </td>
    <td><button type="button" class="btn btn-danger btn-sm remove-row">削除</button></td>
  `;
  table.appendChild(newRow);
  rowCount++;
});

// 行削除処理（イベントバブリング対応）
document.querySelector('#item-table tbody').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
    }
});
</script>
@endsection