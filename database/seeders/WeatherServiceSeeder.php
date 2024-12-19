<?php

namespace Database\Seeders;

use App\Models\WeatherService;
use Illuminate\Database\Seeder;

class WeatherServiceSeeder extends Seeder
{
    public function run()
    {
        WeatherService::create([
            'name' => 'OpenWeatherMap',
            'api_key' => config('services.openweathermap.key'),
            'is_active' => true,
        ]);

        WeatherService::create([
            'name' => 'WeatherAPI',
            'api_key' => config('services.weatherapi.key'),
            'is_active' => true,
        ]);
    }
}
