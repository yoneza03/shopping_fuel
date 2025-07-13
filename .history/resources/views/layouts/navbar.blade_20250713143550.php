<nav class="d-flex justify-content-between align-items-center p-3 bg-light">
    {{-- <a href="{{ route('home') }}" class="btn btn-primary">ホームへ戻る</a> --}}

    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-danger">ログアウト</button>
    </form>
    <a href="{{ route('fuel.entry') }}" class="btn btn-primary">燃費計算</a>
    <a href="{{ route('vehicle.index') }}" class="btn btn-secondary">車種管理</a>
    <a href="{{ route('vehicle.report') }}" class="btn btn-info">車種別レポート</a>
</nav>
