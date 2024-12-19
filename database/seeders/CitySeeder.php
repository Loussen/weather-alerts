<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run()
    {
        $cities = [
            [
                'name' => 'Istanbul',
                'country' => 'Turkey',
                'latitude' => 41.0082,
                'longitude' => 28.9784
            ],
            [
                'name' => 'Ankara',
                'country' => 'Turkey',
                'latitude' => 39.9334,
                'longitude' => 32.8597
            ],
            [
                'name' => 'Izmir',
                'country' => 'Turkey',
                'latitude' => 38.4237,
                'longitude' => 27.1428
            ],
            [
                'name' => 'London',
                'country' => 'United Kingdom',
                'latitude' => 51.5074,
                'longitude' => -0.1278
            ],
            [
                'name' => 'Paris',
                'country' => 'France',
                'latitude' => 48.8566,
                'longitude' => 2.3522
            ],
            [
                'name' => 'Berlin',
                'country' => 'Germany',
                'latitude' => 52.5200,
                'longitude' => 13.4050
            ],
            [
                'name' => 'New York',
                'country' => 'United States',
                'latitude' => 40.7128,
                'longitude' => -74.0060
            ],
            [
                'name' => 'Tokyo',
                'country' => 'Japan',
                'latitude' => 35.6762,
                'longitude' => 139.6503
            ],
            [
                'name' => 'Sydney',
                'country' => 'Australia',
                'latitude' => -33.8688,
                'longitude' => 151.2093
            ],
            [
                'name' => 'Dubai',
                'country' => 'United Arab Emirates',
                'latitude' => 25.2048,
                'longitude' => 55.2708
            ]
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
