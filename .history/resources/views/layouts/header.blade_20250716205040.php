<header class="d-flex justify-content-between align-items-center p-3 bg-light">
  <a href="{{ route('login') }}">
  <img src="{{ asset('images/logo.png') }}" alt="ロゴ" class="logo"></a>
  <a href="{{ route('home') }}" class="btn btn-primary">ホームへ戻る</a>

  @if(Auth::check()) {{-- ログイン済みなら表示 --}}
<form action="{{ route('logout') }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" class="btn btn-danger">ログアウト</button>
</form>
  @endif
</header>

{{-- ログアウトフォーム --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
@csrf
</form>
