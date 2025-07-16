@extends('layouts.app')
@section('title', '権限設定')

@section('content')

<form action="{{ route('admin.update', $user->id) }}" method="POST">
  @csrf
  <div class="form-check">
    <input class="form-check-input" type="radio" name="role" value="view" id="view" checked>
    <label class="form-check-label" for="view">閲覧のみ</label>
  </div>
  <div class="form-check">
    <input class="form-check-input" type="radio" name="role" value="edit" id="edit">
    <label class="form-check-label" for="edit">編集・閲覧OK</label>
  </div>
  <button type="submit" class="btn btn-primary">変更を保存</button>
  <a href="{{ route('admin') }}" class="btn btn-secondary">ホームに戻る</a>
</form>
@endsection