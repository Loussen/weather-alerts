<?php

namespace App\Console\Commands;

use App\Jobs\CheckWeatherConditions;
use Illuminate\Console\Command;

class CheckWeather extends Command
{
    protected $signature = 'weather:check';
    protected $description = 'Check weather conditions and send alerts';

    public function handle()
    {
        CheckWeatherConditions::dispatch();
        $this->info('Weather check job dispatched successfully.');
    }
}
