<header class="d-flex justify-content-between align-items-center p-3 bg-light">
    <a href="{{ route('login') }}">
        <img src="{{ asset('images/logo.png') }}" alt="ロゴ" class="logo"></a>
        <a href="{{ route('home') }}" class="btn btn-primary">ホームへ戻る</a>

@if(Auth::check() && request()->routeIs('home'))
  <a href="#" class="btn btn-danger"
     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
     ログアウト
  </a>

  {{-- ★ フォームはこの場所 or layouts/app.blade.php に書きましょう --}}
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
  </form>
@endif
</header>
