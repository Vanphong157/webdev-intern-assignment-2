<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Mail\WelcomeEmail;
use App\Mail\DailyForecast;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class WeatherSubscriptionController extends Controller
{
    public function register(Request $request)
    {
        $email = $request->input('email');
        $token = Str::uuid()->toString();

        $subscription = Subscription::create([
            'email' => $email,
            'token' => $token,
            'confirmed' => true,
        ]);

        Mail::to($email)->send(new WelcomeEmail($subscription));

        return response()->json(['message' => 'Email đã được đăng ký thành công và sẽ nhận thông tin dự báo thời tiết hàng ngày.']);
    }

    public function unsubscribe(Request $request)
    {
        $email = $request->input('email');

        $subscription = Subscription::where('email', $email)->first();
        if ($subscription) {
            $subscription->delete();
            return response()->json(['message' => 'Hủy đăng ký thành công.']);
        } else {
            return response()->json(['message' => 'Email không tồn tại.']);
        }
    }

    public function sendDailyForecast()
    {
        $subscriptions = Subscription::where('confirmed', true)->get();
        foreach ($subscriptions as $subscription) {
            $weatherInfo = $this->getWeatherInfo('Hanoi');

            Mail::to($subscription->email)->send(new DailyForecast($weatherInfo));
        }
    }

    private function getWeatherInfo($location)
    {
        $apiUrl = config('services.weather.api_url');
        $apiKey = config('services.weather.api_key');
        $url = "{$apiUrl}?key={$apiKey}&q={$location}";

        $response = Http::get($url);
        $data = $response->json();

        if ($data && isset($data['current'])) {
            $current = $data['current'];
            return "Dự báo thời tiết cho {$location}:\n"
                . "Nhiệt độ: {$current['temp_c']}°C\n"
                . "Mức gió: {$current['wind_mph']} Mph\n"
                . "Độ ẩm: {$current['humidity']}%\n";
        }

        return "Không thể lấy thông tin thời tiết.";
    }
}
