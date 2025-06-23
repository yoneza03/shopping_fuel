<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WeatherController extends Controller
{
    public function fetch()
    {
        $city = 'Osaka'; // 都市名は後で動的にしてもOK
        $apiKey = config('services.openweather.key');
        $url = config('services.openweather.url') . "?q={$city}&units=metric&lang=ja&appid={$apiKey}";

        $client = new Client();
        $response = $client->get($url);
        $data = json_decode($response->getBody(), true);

        $weatherType = $data['weather'][0]['main']; // 例: "Clear", "Rain", etc.

        switch ($weatherType) {
            case 'Clear':
                $bgClass = 'bg-sunny';
                break;
            case 'Rain':
                $bgClass = 'bg-rainy';
                break;
            case 'Clouds':
                $bgClass = 'bg-cloudy';
                break;
            default:
                $bgClass = 'bg-default';
                break;
        }

        $condition = $data['weather'][0]['main'];
        $precip = $data['clouds']['all']; // 雲の量で代用（％）

        if ($condition === 'Clear' && $precip < 20) {
            $advice = '今日はお出かけ日和です！洗車もOK 🚗✨';
        } elseif ($condition === 'Rain' || $precip >= 70) {
            $advice = '今日は雨模様。洗車は避けて、おうち時間を楽しんで ☔';
        } elseif ($condition === 'Clouds') {
            $advice = '雲は多めですが、短時間の外出なら問題なさそう 🌥';
        } else {
            $advice = '天気の様子を見ながら予定を調整すると良さそうです。';
        }

        return view('weather', [
            'weather' => $data,
            'bgClass' => $bgClass,
            'advice' => $advice
        ]);

    }

}
