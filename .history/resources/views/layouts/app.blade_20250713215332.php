<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- カスタム CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Chart.js 読込 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    @include('layouts.header')  

    {{--  navbar を読み込み　燃費計算には燃費用ナビバーを表示 --}}
    @if(Auth::check() && (request()->routeIs('fuel.*') || request()->routeIs('vehicle.*')))
        @include('layouts.navbar')
    @endif

    @if(Auth::check())
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @endif
    
    <div class="container">
        @yield('content')  {{-- 各ページの内容をここに埋め込む --}}
    </div>
    
    @yield('scripts')

    @include('layouts.footer')  
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- カスタム JavaScript -->
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>