<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeatherInfoEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $weatherData;

    public function __construct($weatherData)
    {
        $this->weatherData = $weatherData;
    }

    public function build()
    {
        return $this->view('emails.weather_info')
                    ->with(['weatherData' => $this->weatherData])
                    ->subject('Current Weather Information');
    }
}
