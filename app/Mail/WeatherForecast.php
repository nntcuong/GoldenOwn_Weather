<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class WeatherForecast extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $apiKey = env('WEATHER_API_KEY'); 
        $cityName = $this->user->address;

        $responseCurrent = Http::get("https://api.weatherapi.com/v1/current.json", [
            'key' => $apiKey,
            'q' => $cityName,
        ]);

        $weatherResponse = $responseCurrent->json();

        return $this->view('email.mail')
                    ->with(['data' => $weatherResponse])
                    ->subject('Daily Weather Forecast for ' . $cityName);
    }
}
