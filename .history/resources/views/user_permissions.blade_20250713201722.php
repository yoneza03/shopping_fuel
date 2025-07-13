@extends('layouts.app')
@section('title', '権限設定')

@section('content')

<form action="{{ route('admin.update', $user->id) }}" method="POST">
  @csrf
  <div class="mb-3">
    <label>権限設定：</label><br>
    <input type="radio" name="role" value="view" {{ $user->role === 'view' ? 'checked' : '' }}> 閲覧のみ
    <input type="radio" name="role" value="edit" {{ $user->role === 'edit' ? 'checked' : '' }}> 編集・閲覧OK
  </div>

  <button type="submit" class="btn btn-primary">変更を保存</button>
  <a href="{{ route('admin') }}" class="btn btn-secondary">ホームに戻る</a>
</form>
@endsection