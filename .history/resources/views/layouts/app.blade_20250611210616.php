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
</head>
<body>
    @include('layouts.header')  {{-- ヘッダーを読み込み --}}

    {{-- ホーム画面の場合のみ navbar を読み込む --}}
    @if(Auth::check() && request()->routeIs('home'))
        @include('layouts.navbar')
    @endif
    
    <div class="container">
        @yield('content')  {{-- 各ページの内容をここに埋め込む --}}
    </div>

    @include('layouts.footer')  {{-- フッターを読み込み --}}

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- カスタム JavaScript -->
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>