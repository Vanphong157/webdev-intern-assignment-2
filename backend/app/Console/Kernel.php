<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    //tạo Scheduler  với Task Scheduling sử dụng câu lệnh  php artisan send:daily-forecast và cài đặt thời gian chạy để gửi email
 
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('send:daily-forecast')->dailyAt('8:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
