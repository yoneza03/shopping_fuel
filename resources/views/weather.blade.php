@extends('layouts.app')

<div class="p-4 {{ $bgClass }}">
    <h2>{{ $weather['name'] }} の天気</h2>

    <div class="d-flex align-items-center">
        <img src="https://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@2x.png" 
            alt="天気アイコン" class="me-2">
        <div>
            <p>天気：{{ $weather['weather'][0]['description'] }}</p>
            <p>気温：{{ $weather['main']['temp'] }} ℃</p>
            <p>降水確率：{{ $weather['clouds']['all'] }} %</p>
        </div>
    </div>
    <div class="mt-3 advice-box p-3 rounded">
        <strong>アドバイス：</strong> {{ $advice }}
    </div>
</div>