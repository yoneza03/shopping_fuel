<form method="POST" action="{{ route('vehicle.store') }}">
  @csrf
  <input type="text" name="name" placeholder="車種名（例：ステップワゴン）" class="form-control" required>
  <input type="text" name="maker" placeholder="メーカー（例：ホンダ）" class="form-control">
  <button type="submit" class="btn btn-primary mt-2">登録</button>
</form>