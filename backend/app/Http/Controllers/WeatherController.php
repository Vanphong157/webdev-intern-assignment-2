<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeatherInfoEmail;

class WeatherController extends Controller
{
    public function fetchWeather(Request $request)
    {
        $location = $request->input('location');
        $client = new Client();
        
        $apiKey = env('WEATHER_API_KEY');
        $apiUrl = env('WEATHER_API_URL');;

       
        Log::info('API Key: ' . $apiKey);
        Log::info('API URL: ' . $apiUrl);

        try {
            $response = $client->request('GET', "{$apiUrl}/current.json", [
                'query' => [
                    'key' => $apiKey,
                    'q' => $location
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            
           
            Log::info('Weather API Response: ' . $response->getBody());

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error("Error fetching weather: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fetchForecast(Request $request)
    {
        $location = $request->input('location');
        $client = new Client();
 
        $apiKey = env('WEATHER_API_KEY');
        $apiUrl = env('WEATHER_API_URL');;


        Log::info('API Key: ' . $apiKey);
        Log::info('API URL: ' . $apiUrl);

        try {
            $response = $client->request('GET', "{$apiUrl}/forecast.json", [
                'query' => [
                    'key' => $apiKey,
                    'q' => $location,
                    'days' => 5
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            
            // Log the response for debugging
            Log::info('Forecast API Response: ' . $response->getBody());

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error("Error fetching forecast: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function sendWeatherEmail(Request $request)
    {
        $email = $request->input('emailStorge');
        $weatherData = $request->input('weatherData');

        try {
            Mail::to($email)->send(new WeatherInfoEmail($weatherData));
            return response()->json(['message' => 'Weather info sent successfully.']);
        } catch (\Exception $e) {
            
            return response()->json(['message' => 'Error sending weather info.'], 500);
        }
    }
}
