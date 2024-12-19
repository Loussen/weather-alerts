<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\WeatherService as WeatherServiceModel;

class WeatherService
{
    protected array $services;

    public function __construct()
    {
        $this->services = WeatherServiceModel::where('is_active', true)->get();
    }

    public function getWeatherData(float $latitude, float $longitude): array
    {
        $data = [];

        foreach ($this->services as $service) {
            $response = match ($service->name) {
                'OpenWeatherMap' => $this->getOpenWeatherData($service->api_key, $latitude, $longitude),
                'WeatherAPI' => $this->getWeatherAPIData($service->api_key, $latitude, $longitude),
                default => null
            };

            if ($response) {
                $data[] = $response;
            }
        }

        return $this->aggregateWeatherData($data);
    }

    protected function getOpenWeatherData(string $apiKey, float $latitude, float $longitude): ?array
    {
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => $apiKey,
            'units' => 'metric'
        ]);

        if ($response->successful()) {
            return [
                'source' => 'OpenWeatherMap',
                'precipitation' => $response->json('rain.1h', 0),
                'uv_index' => $this->getUVIndex($apiKey, $latitude, $longitude)
            ];
        }

        return null;
    }

    protected function getWeatherAPIData(string $apiKey, float $latitude, float $longitude): ?array
    {
        $response = Http::get("http://api.weatherapi.com/v1/current.json", [
            'key' => $apiKey,
            'q' => "$latitude,$longitude"
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'source' => 'WeatherAPI',
                'precipitation' => $data['current']['precip_mm'] ?? 0,
                'uv_index' => $data['current']['uv'] ?? 0
            ];
        }

        return null;
    }

    protected function aggregateWeatherData(array $data): array
    {
        if (empty($data)) {
            return ['precipitation' => 0, 'uv_index' => 0];
        }

        return [
            'precipitation' => collect($data)->avg('precipitation'),
            'uv_index' => collect($data)->avg('uv_index')
        ];
    }
}
