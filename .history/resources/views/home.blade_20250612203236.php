<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ホーム画面</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('layouts.header')  {{-- ヘッダーを追加 --}}
    {{-- @if(Auth::check())
    <p>ログインしています！</p>
    @else
        <p>ログインしていません。</p>
    @endif
    <div class="container">
        <h1>ようこそ！</h1>
        <p>これはホーム画面です。</p>
    </div> --}}
    <form id="comparison-form">
        <div class="row">
            <!-- 左側入力 -->
            <div class="col-md-5">
                <input type="number" id="price_left" class="form-control" placeholder="価格">
                <input type="number" id="volume_left" class="form-control" placeholder="容量">
                <select id="unit_left" class="form-select">
                    <option value="g">g</option>
                    <option value="kg">kg</option>
                    <option value="ml">ml</option>
                    <option value="L">L</option>
                    <option value="cm">cm</option>
                    <option value="m">m</option>
                </select>
            </div>

            <!-- 計算ボタン -->
            <div class="col-md-2 text-center">
                <button type="button" id="calculate" class="btn btn-primary">計算</button>
            </div>

            <!-- 右側入力 -->
            <div class="col-md-5">
                <input type="number" id="price_right" class="form-control" placeholder="価格">
                <input type="number" id="volume_right" class="form-control" placeholder="容量">
                <select id="unit_right" class="form-select">
                    <option value="g">g</option>
                    <option value="kg">kg</option>
                    <option value="ml">ml</option>
                    <option value="L">L</option>
                    <option value="cm">cm</option>
                    <option value="m">m</option>
                </select>
            </div>
        </div>

        <!-- 操作ボタン -->
        <div class="text-center mt-3">
            <button type="button" id="clear" class="btn btn-secondary">クリア</button>
            <button type="button" id="register" class="btn btn-success">登録</button>
            <button type="button" id="delete" class="btn btn-danger">削除</button>
        </div>

        <!-- 計算結果表示 -->
        <div id="result" class="mt-3"></div>
    </form>

    @include('layouts.footer')  {{-- フッター読込 --}}

    <script src="{{ asset('js/comparison.js') }}"></script>  {{-- 比較計算のjs読込 --}}

</body>
</html>