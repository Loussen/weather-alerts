<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Edit Weather Alert') }}
            </h2>
            <a href="{{ route('weather.alerts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                {{ __('Back to Alerts') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form action="{{ route('weather.alerts.update', $alert) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- City Selection -->
                        <div>
                            <x-label for="city_id" value="{{ __('City') }}" />
                            <select id="city_id" name="city_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ $city->id == $alert->city_id ? 'selected' : '' }}>
                                        {{ $city->name }}, {{ $city->country }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="city_id" class="mt-2" />
                        </div>

                        <!-- UV Threshold -->
                        <div>
                            <x-label for="uv_threshold" value="{{ __('UV Index Threshold') }}" />
                            <div class="mt-1">
                                <x-input
                                    id="uv_threshold"
                                    type="number"
                                    name="uv_threshold"
                                    step="0.1"
                                    min="0"
                                    max="15"
                                    value="{{ old('uv_threshold', $alert->uv_threshold) }}"
                                    class="block w-full"
                                />
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ __('You will be notified when UV index exceeds this value (0-15)') }}
                                </p>
                            </div>
                            <x-input-error for="uv_threshold" class="mt-2" />
                        </div>

                        <!-- Precipitation Threshold -->
                        <div>
                            <x-label for="precipitation_threshold" value="{{ __('Precipitation Threshold (mm/h)') }}" />
                            <div class="mt-1">
                                <x-input
                                    id="precipitation_threshold"
                                    type="number"
                                    name="precipitation_threshold"
                                    step="0.1"
                                    min="0"
                                    value="{{ old('precipitation_threshold', $alert->precipitation_threshold) }}"
                                    class="block w-full"
                                />
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ __('You will be notified when precipitation exceeds this value in mm/hour') }}
                                </p>
                            </div>
                            <x-input-error for="precipitation_threshold" class="mt-2" />
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                name="is_active"
                                id="is_active"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                {{ $alert->is_active ? 'checked' : '' }}
                            >
                            <x-label for="is_active" class="ml-2" value="{{ __('Active') }}" />
                        </div>

                        <!-- Current Status Info -->
                        @if($alert->isPaused())
                            <div class="p-4 bg-yellow-50 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            This alert is currently paused until {{ $alert->paused_until->format('M d, H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('weather.alerts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 mr-2">
                                {{ __('Cancel') }}
                            </a>
                            <x-button>
                                {{ __('Update Alert') }}
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
