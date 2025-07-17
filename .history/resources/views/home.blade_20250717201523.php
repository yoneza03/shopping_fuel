@extends('layouts.app')

@section('title', 'ホーム画面')

@section('content')
<div class="d-flex">
    <!-- 比較計算フォーム -->
    <div id="comparison-wrapper" class="flex-grow-1">
        <form id="comparison-form">
            <div class="d-flex justify-content-between gap-4">
                <!-- 左側入力 -->
                <div class="form-section">
                    <input type="number" id="price_left" class="form-control mb-2" placeholder="価格">
                    <input type="number" id="volume_left" class="form-control mb-2" placeholder="容量">
                    <select id="unit_left" class="form-select mb-2">
                        <option value="g">g</option>
                        <option value="kg">kg</option>
                        <option value="ml">ml</option>
                        <option value="L">L</option>
                        <option value="cm">cm</option>
                        <option value="m">m</option>
                    </select>
                </div>

                <!-- 計算ボタン -->
                <div class="button-section">
                    <button type="button" id="calculate" class="btn btn-primary">計算</button>
                </div>

                <!-- 右側入力 -->
                <div class="form-section">
                    <input type="number" id="price_right" class="form-control mb-2" placeholder="価格">
                    <input type="number" id="volume_right" class="form-control mb-2" placeholder="容量">
                    <select id="unit_right" class="form-select mb-2">                  
                        <option value="g">g</option>
                        <option value="kg">kg</option>
                        <option value="ml">ml</option>
                        <option value="L">L</option>
                        <option value="cm">cm</option>
                        <option value="m">m</option>
                    </select>
                </div>
            
            </div>       
        </form>
        <hr>
        <!-- 操作ボタン -->
        <div class="text-center mt-3">
        <button type="button" id="clear-left" class="btn btn-success me-4">左側をクリア</button>
        <button type="button" id="clear-right" class="btn btn-warning">右側をクリア</button>
        </div>
        <!-- 計算結果表示 -->
        <div id="result" class="mt-3"></div>
        <hr>

        <div class="container card-section mt-5 mb-4">
            @yield('content')
            <!-- ボタン群：カメラ／買い物／燃費／履歴 -->
            <div class="mt-4 w-100 d-flex justify-content-around align-items-center" id="icon-button-group">
                <a href="{{ route('camera.start') }}" class="text-center text-decoration-none">
                    <img src="{{ asset('images/camera.png') }}" alt="カメラ" class="hover-icon" width="48">
                    <div class="small">レシート撮影</div>
                </a>
                <a href="{{ route('shopping.entry') }}" class="text-center text-decoration-none">
                    <img src="{{ asset('images/shopping.png') }}" alt="買い物" class="hover-icon" width="48">
                    <div class="small">買い物データ</div>
                </a>
                <a href="{{ route('fuel.entry') }}" class="text-center text-decoration-none">
                    <img src="{{ asset('images/fuel.png') }}" alt="燃費" class="hover-icon" width="56">
                    <div class="small">燃費記録</div>
                </a>
                <a href="{{ route('history.search') }}" class="text-center text-decoration-none">
                    <img src="{{ asset('images/history.png') }}" alt="履歴" class="hover-icon" width="48">
                    <div class="small">履歴一覧</div>
                </a>
                <a href="{{ route('admin') }}" class="text-center text-decoration-none">
                    <img src="{{ asset('images/admin.png') }}" alt="管理者" class="hover-icon" width="60">
                    <div class="small">管理者ページ</div>
                </a>
            </div>
        </div>
    </div>
    <!-- 天気API -->
    <div class="ms-4 mt-5">
        @include('components.weather', [
            'weather' => $weather,
            'dailyForecasts' => $dailyForecasts,
            'bgClass' => $bgClass,
            'advice' => $advice
        ])
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/comparison.js') }}"></script>  {{-- 比較計算のjs読込 --}}
@endsection
