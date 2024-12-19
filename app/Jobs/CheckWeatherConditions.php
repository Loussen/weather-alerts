<?php

namespace App\Jobs;

use App\Models\WeatherAlert;
use App\Services\WeatherService;
use App\Notifications\WeatherAlertNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckWeatherConditions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(WeatherService $weatherService)
    {
        WeatherAlert::with(['user', 'city'])
            ->where('is_active', true)
            ->whereNull('paused_until')
            ->orWhere('paused_until', '<', now())
            ->chunk(100, function ($alerts) use ($weatherService) {
                foreach ($alerts as $alert) {
                    $this->processAlert($alert, $weatherService);
                }
            });
    }

    protected function processAlert(WeatherAlert $alert, WeatherService $weatherService)
    {
        $weatherData = $weatherService->getWeatherData(
            $alert->city->latitude,
            $alert->city->longitude
        );

        $notifications = [];

        if ($weatherData['precipitation'] > $alert->precipitation_threshold) {
            $notifications[] = [
                'type' => 'precipitation',
                'message' => "High precipitation alert for {$alert->city->name}! Current: {$weatherData['precipitation']}mm/h"
            ];
        }

        if ($weatherData['uv_index'] > $alert->uv_threshold) {
            $notifications[] = [
                'type' => 'uv',
                'message' => "High UV index alert for {$alert->city->name}! Current: {$weatherData['uv_index']}"
            ];
        }

        foreach ($notifications as $notification) {
            $alert->user->notify(new WeatherAlertNotification(
                $alert->city,
                $notification['type'],
                $notification['message']
            ));
        }
    }
}
