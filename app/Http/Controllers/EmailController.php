<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        $weatherResponse = [];

        $apiKey = env('WEATHER_API_KEY'); 

        $user = Auth::user();
        $cityName = $user ? $user->address : $request->city; 

        $responseCurrent = Http::get("https://api.weatherapi.com/v1/current.json", [
            'key' => $apiKey,
            'q' => $cityName
        ]);
        
        $weatherResponse = $responseCurrent->json();
        
        return view('email.mail', [
            'data' => $weatherResponse,
        ]);
    }
}
