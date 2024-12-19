<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\WeatherAlert;
use Illuminate\Http\Request;
use App\Services\WeatherService;

class WeatherAlertController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
        $this->middleware('auth');
    }

    public function index()
    {
        $alerts = auth()->user()->weatherAlerts()
            ->with('city')
            ->get();

        return view('weather.alerts.index', compact('alerts'));
    }

    public function create()
    {
        $cities = City::orderBy('name')->get();
        return view('weather.alerts.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'uv_threshold' => 'required|numeric|min:0|max:15',
            'precipitation_threshold' => 'required|numeric|min:0',
        ]);

        auth()->user()->weatherAlerts()->create($validated);

        return redirect()
            ->route('weather.alerts.index')
            ->with('success', 'Weather alert created successfully.');
    }

    public function edit(WeatherAlert $alert)
    {
        $this->authorize('update', $alert);
        $cities = City::orderBy('name')->get();

        return view('weather.alerts.edit', compact('alert', 'cities'));
    }

    public function update(Request $request, WeatherAlert $alert)
    {
        $this->authorize('update', $alert);

        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'uv_threshold' => 'required|numeric|min:0|max:15',
            'precipitation_threshold' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $alert->update($validated);

        return redirect()
            ->route('weather.alerts.index')
            ->with('success', 'Weather alert updated successfully.');
    }

    public function destroy(WeatherAlert $alert)
    {
        $this->authorize('delete', $alert);

        $alert->delete();

        return redirect()
            ->route('weather.alerts.index')
            ->with('success', 'Weather alert deleted successfully.');
    }

    public function pause(Request $request, WeatherAlert $alert)
    {
        $this->authorize('update', $alert);

        $validated = $request->validate([
            'hours' => 'required|integer|min:1|max:72'
        ]);

        $alert->update([
            'paused_until' => now()->addHours($validated['hours'])
        ]);

        return redirect()
            ->route('weather.alerts.index')
            ->with('success', "Alert paused for {$validated['hours']} hours.");
    }
}
