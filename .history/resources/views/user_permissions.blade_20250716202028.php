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
  <button class="btn btn-primary me-2">
    <i class="bi bi-save"></i> 変更を保存
  </button>

  <a href="{{ route('home') }}" class="btn btn-secondary">
    <i class="bi bi-house-door"></i> ホームに戻る
  </a>
</form>
@endsection