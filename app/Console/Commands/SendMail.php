<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendMail extends Command
{
    protected $signature = 'users:sendmail';
    protected $description = 'Send mail to users every day';

    public function handle()
    {
        $users = User::where('is_confirmed', true)->get();
        $apiKey = env('WEATHER_API_KEY');

        foreach ($users as $user) {
            $weatherResponse = [];
            $cityName = $user->address;

            $responseCurrent = Http::get("https://api.weatherapi.com/v1/current.json", [
                'key' => $apiKey,
                'q' => $cityName,
            ]);


            if ($responseCurrent->successful()) {
                $weatherResponse = $responseCurrent->json();

                try {
                    Mail::send('email.mail', ['data' => $weatherResponse], function ($message) use ($user) {
                        $message->to($user->email)->subject('Daily Weather Forecast');
                    });
                    $this->info('Email sent to ' . $user->email);
                } catch (\Exception $e) {
                    Log::error('Error sending email to ' . $user->email . ': ' . $e->getMessage());
                    $this->error('Error sending email to ' . $user->email);
                }
            } else {
                Log::error('Error fetching weather data for ' . $cityName . ': ' . $responseCurrent->body());
                $this->error('Error fetching weather data for ' . $cityName);
            }
        }

        $this->info('Emails sent successfully!');
    }
}
