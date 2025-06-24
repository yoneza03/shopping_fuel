
<div id="weather-widget" class="bg-light rounded p-3" style="width: 300px;">
    <h5 class="fw-bold">{{ $weather['name'] }}の天気</h5>
    <div class="d-flex align-items-center mb-2">
        <img src="https://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@2x.png" alt="天気アイコン" class="me-2">
        <div>
            <p>天気：{{ $weather['weather'][0]['description'] }}</p>
            <p>気温：{{ $weather['main']['temp'] }} ℃</p>
            <p>降水確率：{{ $weather['clouds']['all'] }} %</p>
        </div>
    </div>
    <div class="mb-2 small">{{ $advice }}</div>
    <hr class="my-2">
    <h6 class="fw-bold mb-2">週間天気</h6>
    @foreach ($dailyForecasts as $day)
        <div class="d-flex align-items-center justify-content-between mb-1">
            <span class="small">{{ \Carbon\Carbon::parse($day['date'])->format('n/j (D)') }}</span>
            <img src="https://openweathermap.org/img/wn/{{ $day['icon'] }}.png" width="30" alt="">
            <span class="small">{{ round($day['temp']) }} ℃</span>
        </div>
    @endforeach
</div>