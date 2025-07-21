<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WeatherController extends Controller
{
    public function fetch()
    {
        $city = 'Nara'; // 都市名は後で動的にしてもOK
        $apiKey = config('services.openweather.key');
        $client = new \GuzzleHttp\Client();

        try {
            $currentUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&units=metric&lang=ja&appid={$apiKey}";
            $currentResponse = $client->get($currentUrl);
            $currentData = json_decode($currentResponse->getBody(), true);

            $forecastUrl = "https://api.openweathermap.org/data/2.5/forecast?q={$city}&units=metric&lang=ja&appid={$apiKey}";
            $forecastResponse = $client->get($forecastUrl);
            $forecastData = json_decode($forecastResponse->getBody(), true);

            // 1日ごとに絞る（最大5日分）
            $dailyForecasts = [];
            $usedDates = [];

            foreach ($forecastData['list'] as $entry) {
                $date = substr($entry['dt_txt'], 0, 10);
                if (!in_array($date, $usedDates)) {
                    $dailyForecasts[] = [
                        'date' => $date,
                        'temp' => $entry['main']['temp'],
                        'description' => $entry['weather'][0]['description'],
                        'icon' => $entry['weather'][0]['icon'],
                    ];
                    $usedDates[] = $date;
                }
                if (count($dailyForecasts) >= 5) break;
            }


            $weatherType = $currentData['weather'][0]['main']; // 例: "Clear", "Rain", etc.

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

            $condition = $currentData['weather'][0]['main'];
            $precip = $currentData['clouds']['all']; // 雲の量で代用（％）

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
                'weather' => $currentData,
                'dailyForecasts' => $dailyForecasts,
                'bgClass' => $bgClass,
                'advice' => $advice
            ]);

        } catch (\Exception $e) {
                Log::error('天気API通信エラー: ' . $e->getMessage());
            return view('weather', [
                'weather' => null,
                'dailyForecasts' => [],
                'bgClass' => 'bg-default',
                'advice' => '天気情報の取得に失敗しました。'
            ]);
        }
    }

}
