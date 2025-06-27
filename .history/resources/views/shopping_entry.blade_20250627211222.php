
@extends('layouts.app')

@section('title', '買い物データ入力')

@section('content')
<div class="container">

  <h2 class="mb-4">買い物データ入力</h2>

  <form action="{{ route('shopping.confirm') }}" method="POST" enctype="multipart/form-data" id="shopping-form">
    @csrf

    <!-- 店舗名・購入日 -->
    <div class="row mb-3">
      <div class="col-md-6">
        <label for="store" class="form-label">店舗名</label>
        <input type="text" name="store" id="store" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label for="date" class="form-label">購入日</label>
        <input type="date" name="date" id="date" class="form-control" required>
      </div>
    </div>

    <!-- 商品入力（テーブル） -->
    <table class="table table-bordered" id="item-table">
      <thead>
        <tr>
          <th>商品名</th>
          <th>価格</th>
          {{-- <th></th> --}}
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="text" name="items[0][name]" class="form-control" required></td>
          <td><input type="number" name="items[0][price]" class="form-control" required></td>
         <button type="button" class="btn btn-danger btn-sm remove-row">削除</button>
        </tr>
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
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script>
let rowCount = 1;
document.getElementById('add-row').addEventListener('click', () => {
    const table = document.querySelector('#item-table tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
      <td><input type="text" name="items[${rowCount}][name]" class="form-control" required></td>
      <td><input type="number" name="items[${rowCount}][price]" class="form-control" required></td>
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