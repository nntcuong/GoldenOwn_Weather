<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyWeatherMail extends Mailable
{
    use Queueable, SerializesModels;

    public $weather;

    public function __construct($weather)
    {
        $this->weather = $weather;
    }

    public function build()
    {
        return $this->view('emails.daily_weather')
            ->subject('Your Daily Weather Forecast')
            ->with(['weather' => $this->weather]);
    }
}
