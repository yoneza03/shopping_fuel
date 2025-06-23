<h2>{{ $weather['name'] }} の天気</h2>
<p>天気：{{ $weather['weather'][0]['description'] }}</p>
<p>気温：{{ $weather['main']['temp'] }} ℃</p>
<p>降水確率：{{ $weather['clouds']['all'] }} %</p>