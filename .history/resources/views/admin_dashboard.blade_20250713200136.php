@extends('layouts.app')
@section('title', '管理者権限')

@section('content')
<table class="table table-striped">
  <thead>
    <tr><th>ID</th><th>名前</th><th>メール</th><th>権限</th><th>操作</th></tr>
  </thead>
  <tbody>
    @foreach ($users as $user)
      <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->role }}</td>
        <td>
          <a href="{{ route('admin.edit', $user->id) }}" class="btn btn-sm btn-primary">編集</a>
          <form action="{{ route('admin.destroy', $user->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？')" style="display:inline">
            @csrf
            <button class="btn btn-sm btn-danger">削除</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection