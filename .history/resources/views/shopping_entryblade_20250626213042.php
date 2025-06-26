
<form action="{{ route('shopping.confirm') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <!-- 店舗＋日付 -->
  <div class="d-flex gap-3 mb-3">
    <input type="text" name="store" class="form-control" placeholder="店舗名">
    <input type="date" name="date" class="form-control">
  </div>

  <!-- 商品入力（動的に追加可能） -->
  <table class="table" id="item-table">
    <thead>
      <tr><th>商品名</th><th>価格</th><th>操作</th></tr>
    </thead>
    <tbody>
      <tr>
        <td><input type="text" name="items[0][name]" class="form-control"></td>
        <td><input type="number" name="items[0][price]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">削除</button></td>
      </tr>
    </tbody>
  </table>
  <button type="button" class="btn btn-outline-secondary btn-sm" id="add-row">商品を追加</button>

  <!-- 添付ファイル -->
  <div class="mt-3">
    <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf" class="form-control">
  </div>

  <!-- 確認ボタン -->
  <div class="mt-3">
    <button type="submit" class="btn btn-primary">確認</button>
  </div>
</form>