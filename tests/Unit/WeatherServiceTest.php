<?php

namespace Tests\Unit;

use App\Services\WeatherService;
use App\Models\WeatherService as WeatherServiceModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeatherServiceTest extends TestCase
{
    use RefreshDatabase;

    protected WeatherService $weatherService;

    protected function setUp(): void
    {
        parent::setUp();

        WeatherServiceModel::factory()->create([
            'name' => 'OpenWeatherMap',
            'api_key' => 'test-key',
            'is_active' => true
        ]);

        $this->weatherService = new WeatherService();
    }

    public function test_get_weather_data_returns_valid_format()
    {
        Http::fake([
            'api.openweathermap.org/*' => Http::response([
                'rain' => ['1h' => 5.2],
                'current' => ['uv' => 7.8]
            ], 200)
        ]);

        $data = $this->weatherService->getWeatherData(41.0082, 28.9784);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('precipitation', $data);
        $this->assertArrayHasKey('uv_index', $data);
    }

    public function test_aggregates_multiple_service_data()
    {
        WeatherServiceModel::factory()->create([
            'name' => 'WeatherAPI',
            'api_key' => 'test-key-2',
            'is_active' => true
        ]);

        Http::fake([
            'api.openweathermap.org/*' => Http::response([
                'rain' => ['1h' => 5.0],
                'current' => ['uv' => 8.0]
            ], 200),
            'api.weatherapi.com/*' => Http::response([
                'current' => [
                    'precip_mm' => 3.0,
                    'uv' => 6.0
                ]
            ], 200)
        ]);

        $data = $this->weatherService->getWeatherData(41.0082, 28.9784);

        $this->assertEquals(4.0, $data['precipitation']); // Average of 5.0 and 3.0
        $this->assertEquals(7.0, $data['uv_index']); // Average of 8.0 and 6.0
    }
}
