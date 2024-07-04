<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyForecast extends Mailable
{
    use Queueable, SerializesModels;

    public $weatherInfo;

    public function __construct($weatherInfo)
    {
        $this->weatherInfo = $weatherInfo;
    }

    public function build()
    {
        return $this->subject('Dự báo thời tiết hàng ngày')
                    ->view('emails.daily_forecast') // Đảm bảo đường dẫn đúng
                    ->with('weatherInfo', $this->weatherInfo);
    }
}

