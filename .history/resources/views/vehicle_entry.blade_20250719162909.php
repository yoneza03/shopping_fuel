@extends('layouts.app')

@section('title', '車種登録')

@section('content')
<div class="container">
  <h2 class="mb-4">🚙 車種登録</h2>

  @if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
  @endif

  <!-- 登録フォーム -->
  <form action="{{ route('vehicle.store') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-2">
      <div class="col-md-4">
        <label class="form-label">車種名</label>
        <input type="text" name="name" class="form-control" placeholder="例：ステップワゴン" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">メーカー</label>
        <input type="text" name="maker" class="form-control" placeholder="例：ホンダ">
      </div>
      <div class="col-md-4">
        <label class="form-label">年式</label>
        <input type="number" name="year" class="form-control" placeholder="例：2022">
      </div>
    </div>
    <div class="text-end mt-3">
      <button type="submit" class="btn btn-primary">登録</button>
    </div>
  </form>

  <!-- 車種一覧 -->
  <h4 class="mb-2">登録済み車種一覧</h4>
  @if ($vehicles->count())
    <table class="table table-bordered">
      <thead><tr><th>ID</th><th>車種名</th><th>メーカー</th><th>年式</th><th>操作</th></tr></thead>
      <tbody>
        @foreach ($vehicles as $v)
          <tr>
            <td>{{ $v->id }}</td>
            <td>{{ $v->name }}</td>
            <td>{{ $v->maker }}</td>
            <td>{{ $v->year ?? '—' }}</td>
            <td class="text-center">
              <div class="d-flex justify-content-center">
                <a href="{{ route('vehicle.edit', $v->id) }}" class="btn btn-sm btn-warning me-4">編集</a>

                <form action="{{ route('vehicle.destroy', $v->id) }}" method="POST" style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('削除してもよろしいですか？')">削除</button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p>まだ車種が登録されていません。</p>
  @endif
</div>

<div class="mb-4">
  <a href="{{ route('fuel.entry') }}" class="btn btn-outline-primary">
    燃費計算フォームへ戻る
  </a>
</div>
@endsection