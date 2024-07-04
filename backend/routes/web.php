<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WeatherController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WeatherSubscriptionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Http;


Route::post('/send-weather-email', [WeatherController::class, 'sendWeatherEmail']);

Route::post('/register', [WeatherSubscriptionController::class, 'register']);
Route::post('/unsubscribe', [WeatherSubscriptionController::class, 'unsubscribe']);

Route::post('/fetch-weather', [WeatherController::class, 'fetchWeather']);
Route::post('/fetch-forecast', [WeatherController::class, 'fetchForecast']);

Route::get('/', function () {
    return view('welcome');
});
