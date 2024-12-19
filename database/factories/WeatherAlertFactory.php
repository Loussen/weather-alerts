<?php

namespace Database\Factories;

use App\Models\WeatherAlert;
use App\Models\User;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeatherAlertFactory extends Factory
{
    protected $model = WeatherAlert::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'city_id' => City::factory(),
            'uv_threshold' => $this->faker->randomFloat(1, 4, 11),
            'precipitation_threshold' => $this->faker->randomFloat(1, 5, 30),
            'is_active' => true,
            'paused_until' => null,
        ];
    }

    public function paused()
    {
        return $this->state(function (array $attributes) {
            return [
                'paused_until' => now()->addHours($this->faker->numberBetween(1, 24)),
            ];
        });
    }
}
