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

        return view('weather', ['weather' => $data]);
    }

}
