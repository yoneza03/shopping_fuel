@extends('layouts.app')
@section('title', '権限設定')

@section('content')
<div class="container mt-5 mb-5">
  <div class="row justify-content-center">
    <div class="col-md-8 bg-white p-4 rounded shadow-sm">
      <h3 class="mb-4 text-center"><i class="bi bi-person-gear"></i> 権限設定</h3>

      <div class="row">
        <div class="col-md-6 pl-4">
          <h5>ユーザー情報</h5>
          <p>ID: {{ $user->id }}</p>
          <p>名前: {{ $user->name }}</p>
          <p>メール: {{ $user->email }}</p>
        </div>
        <div class="col-md-6">
          <form action="{{ route('admin.update', $user->id) }}" method="POST">
            @csrf
            <div class="form-check mb-2">
              <input class="form-check-input" type="radio" name="role" value="view" id="view" checked>
              <label class="form-check-label" for="view">閲覧のみ</label>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="radio" name="role" value="edit" id="edit">
              <label class="form-check-label" for="edit">編集・閲覧OK</label>
            </div>
            <button class="btn btn-primary me-2">
              <i class="bi bi-save"></i> 変更を保存
            </button>
            {{-- <a href="{{ route('home') }}" class="btn btn-secondary">
              <i class="bi bi-house-door"></i> ホームに戻る
            </a> --}}
          </form>
        </div>
      </div>
    </div>
  </div>
</div>@endsection