<?php

namespace Database\Factories;

use App\Models\WeatherService;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeatherServiceFactory extends Factory
{
    protected $model = WeatherService::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['OpenWeatherMap', 'WeatherAPI']),
            'api_key' => $this->faker->uuid,
            'is_active' => true,
        ];
    }
}
