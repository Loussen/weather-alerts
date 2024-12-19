<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\User;
use App\Models\WeatherAlert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeatherAlertTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected City $city;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->city = City::factory()->create([
            'name' => 'Istanbul',
            'country' => 'Turkey',
            'latitude' => 41.0082,
            'longitude' => 28.9784
        ]);
    }

    public function test_user_can_view_alerts_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('weather.alerts.index'));

        $response->assertStatus(200);
        $response->assertViewIs('weather.alerts.index');
    }

    public function test_user_can_create_alert()
    {
        $alertData = [
            'city_id' => $this->city->id,
            'uv_threshold' => 7.5,
            'precipitation_threshold' => 15.0
        ];

        $response = $this->actingAs($this->user)
            ->post(route('weather.alerts.store'), $alertData);

        $response->assertRedirect(route('weather.alerts.index'));
        $this->assertDatabaseHas('weather_alerts', [
            'user_id' => $this->user->id,
            'city_id' => $this->city->id,
            'uv_threshold' => 7.5,
            'precipitation_threshold' => 15.0
        ]);
    }

    public function test_user_can_update_alert()
    {
        $alert = WeatherAlert::factory()->create([
            'user_id' => $this->user->id,
            'city_id' => $this->city->id
        ]);

        $updateData = [
            'city_id' => $this->city->id,
            'uv_threshold' => 8.0,
            'precipitation_threshold' => 20.0,
            'is_active' => true
        ];

        $response = $this->actingAs($this->user)
            ->put(route('weather.alerts.update', $alert), $updateData);

        $response->assertRedirect(route('weather.alerts.index'));
        $this->assertDatabaseHas('weather_alerts', [
            'id' => $alert->id,
            'uv_threshold' => 8.0,
            'precipitation_threshold' => 20.0
        ]);
    }

    public function test_user_can_delete_alert()
    {
        $alert = WeatherAlert::factory()->create([
            'user_id' => $this->user->id,
            'city_id' => $this->city->id
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('weather.alerts.destroy', $alert));

        $response->assertRedirect(route('weather.alerts.index'));
        $this->assertDatabaseMissing('weather_alerts', ['id' => $alert->id]);
    }

    public function test_user_can_pause_alert()
    {
        $alert = WeatherAlert::factory()->create([
            'user_id' => $this->user->id,
            'city_id' => $this->city->id
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('weather.alerts.pause', $alert), ['hours' => 24]);

        $response->assertRedirect(route('weather.alerts.index'));

        $alert->refresh();
        $this->assertTrue($alert->isPaused());
        $this->assertTrue($alert->paused_until->isFuture());
    }
}
