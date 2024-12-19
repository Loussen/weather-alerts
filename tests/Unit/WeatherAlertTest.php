<?php

namespace Tests\Unit;

use App\Models\WeatherAlert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeatherAlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_alert_is_paused_when_pause_time_is_future()
    {
        $alert = WeatherAlert::factory()->create([
            'paused_until' => now()->addHour()
        ]);

        $this->assertTrue($alert->isPaused());
    }

    public function test_alert_is_not_paused_when_pause_time_is_past()
    {
        $alert = WeatherAlert::factory()->create([
            'paused_until' => now()->subHour()
        ]);

        $this->assertFalse($alert->isPaused());
    }

    public function test_alert_is_not_paused_when_pause_time_is_null()
    {
        $alert = WeatherAlert::factory()->create([
            'paused_until' => null
        ]);

        $this->assertFalse($alert->isPaused());
    }
}
