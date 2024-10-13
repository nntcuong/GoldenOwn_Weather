<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Weather;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class WeatherController extends Controller
{

    public function index(Request $request)
{
    $weatherResponse = [];
    $futureResponse = [];
    $weatherCurrentResponse = [];

    $apiKey = env('WEATHER_API_KEY');

    if ($request->isMethod("post")) {
        if ($request->has('city')) {

            $cityName = $request->city;

            $cacheKey = $cityName . '_' . Carbon::now()->format('Y-m-d');

            // Xóa cache cũ nếu tồn tại
            if (Cache::has($cacheKey)) {
                Cache::forget($cacheKey);
            }

            $responseCurrent = Http::get("https://api.weatherapi.com/v1/current.json", [
                'key' => $apiKey,
                'q' => $cityName
            ]);
            $weatherResponse = $responseCurrent->json();

            $days = "14";
            $responseFuture = Http::get("https://api.weatherapi.com/v1/forecast.json", [
                'key' => $apiKey,
                'q' => $cityName,
                'days' => $days
            ]);
            $futureResponse = $responseFuture->json();

            Cache::put($cacheKey, [
                'current' => $weatherResponse,
                'forecast' => $futureResponse,
            ], now()->endOfDay());

        } elseif ($request->has('latitude') && $request->has('longitude')) {

            $latitude = $request->latitude;
            $longitude = $request->longitude;

            $q = $latitude . ',' . $longitude;

            $cacheKey = $q . '_' . Carbon::now()->format('Y-m-d');

            // Xóa cache cũ nếu tồn tại
            if (Cache::has($cacheKey)) {
                Cache::forget($cacheKey);
            }

            $responseCurrent = Http::get("https://api.weatherapi.com/v1/current.json", [
                'key' => $apiKey,
                'q' => $q
            ]);
            $weatherResponse = $responseCurrent->json();
            $cityName = $weatherResponse['location']['name'];

            $days = "14";
            $responseFuture = Http::get("https://api.weatherapi.com/v1/forecast.json", [
                'key' => $apiKey,
                'q' => $cityName,
                'days' => $days
            ]);
            $futureResponse = $responseFuture->json();

            Cache::put($cacheKey, [
                'current' => $weatherResponse,
                'forecast' => $futureResponse,
            ], now()->endOfDay());
        }
    }

    return view('weather', [
        'data' => $weatherResponse,
        'dataFuture' => $futureResponse
    ]);
}


    public function getWeatherHistory(Request $request)
    {
        $cityName = $request->input('cityName');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $cacheKey = ($cityName) ? $cityName . '_' . Carbon::now()->format('Y-m-d') :
            $latitude . ',' . $longitude . '_' . Carbon::now()->format('Y-m-d');

        if (Cache::has($cacheKey)) {
            $cachedData = Cache::get($cacheKey);
            return response()->json([
                'status' => 'success',
                'data' => $cachedData
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No cached data found for today.'
        ]);
    }
}
