<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Mail\DailyForecast;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class SendDailyForecast extends Command
{
    protected $signature = 'send:daily-forecast';
    protected $description = 'Send daily weather forecast to confirmed subscriptions';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $subscriptions = Subscription::where('confirmed', true)->get();
        foreach ($subscriptions as $subscription) {
            $weatherInfo = $this->getWeatherInfo('Hanoi');

            try {
                Mail::to($subscription->email)->send(new DailyForecast($weatherInfo));
                \Log::info("Email sent to: {$subscription->email}");
                \Log::info("Email sent to: {$weatherInfo}");
            } catch (\Exception $e) {
                \Log::error("Failed to send email to: {$subscription->email}, Error: " . $e->getMessage());
            }
        }
    }

    private function getWeatherInfo($location)
    {
        $apiKey = env('WEATHER_API_KEY');
        $apiUrl = env('WEATHER_API_URL');;


        $url = "{$apiUrl}/current.json?key={$apiKey}&q={$location}";

        try {
            $response = Http::timeout(30)->get($url); // Tăng thời gian timeout lên 30 giây
            $data = $response->json();

            if ($data && isset($data['current'])) {
                $current = $data['current'];
                return "Dự báo thời tiết cho {$location}:\n"
                    . "Nhiệt độ: {$current['temp_c']}°C\n"
                    . "Mức gió: {$current['wind_mph']} Mph\n"
                    . "Độ ẩm: {$current['humidity']}%\n";
            }

            return "Không thể lấy thông tin thời tiết.";
        } catch (\Exception $e) {
            \Log::error('Error fetching weather information: ' . $e->getMessage());
            return "Không thể lấy thông tin thời tiết.";
        }
    }
}
