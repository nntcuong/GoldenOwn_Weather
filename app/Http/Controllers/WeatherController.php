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
        $apiKey = env('WEATHER_API_KEY');
    
        if ($request->isMethod("post")) {
            if ($request->has('city')) {
                $cityName = $request->city;
    
                $responseCurrent = Http::get("https://api.weatherapi.com/v1/current.json", [
                    'key' => $apiKey,
                    'q' => $cityName
                ]);
                $weatherResponse = $responseCurrent->json();
    
                // Kiểm tra nếu không có dữ liệu trả về
                if (!isset($weatherResponse['location'])) {
                    return redirect()->back()->withErrors(['error' => 'City not found or invalid.']);
                }
    
                $days = "14";
                $responseFuture = Http::get("https://api.weatherapi.com/v1/forecast.json", [
                    'key' => $apiKey,
                    'q' => $cityName,
                    'days' => $days
                ]);
                $futureResponse = $responseFuture->json();
            } elseif ($request->has('latitude') && $request->has('longitude')) {
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $q = $latitude . ',' . $longitude;
    
                $responseCurrent = Http::get("https://api.weatherapi.com/v1/current.json", [
                    'key' => $apiKey,
                    'q' => $q
                ]);
                $weatherResponse = $responseCurrent->json();
    
                // Kiểm tra nếu không có dữ liệu trả về
                if (!isset($weatherResponse['location'])) {
                    return redirect()->back()->withErrors(['error' => 'Location not found or invalid.']);
                }
    
                $days = "14";
                $responseFuture = Http::get("https://api.weatherapi.com/v1/forecast.json", [
                    'key' => $apiKey,
                    'q' => $weatherResponse['location']['name'],
                    'days' => $days
                ]);
                $futureResponse = $responseFuture->json();
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
