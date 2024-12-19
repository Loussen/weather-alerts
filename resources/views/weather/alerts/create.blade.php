<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Create Weather Alert') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form action="{{ route('weather.alerts.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="space-y-6">
                        <!-- City Selection -->
                        <div>
                            <x-label for="city_id" value="{{ __('City') }}" />
                            <select id="city_id" name="city_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">{{ __('Select a city') }}</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">
                                        {{ $city->name }}, {{ $city->country }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="city_id" class="mt-2" />
                        </div>

                        <!-- UV Threshold -->
                        <div>
                            <x-label for="uv_threshold" value="{{ __('UV Index Threshold') }}" />
                            <x-input id="uv_threshold" type="number" name="uv_threshold" step="0.1" min="0" max="15"
                                     value="{{ old('uv_threshold', 6.0) }}" class="mt-1 block w-full" required />
                            <x-input-error for="uv_threshold" class="mt-2" />
                        </div>

                        <!-- Precipitation Threshold -->
                        <div>
                            <x-label for="precipitation_threshold" value="{{ __('Precipitation Threshold (mm/h)') }}" />
                            <x-input id="precipitation_threshold" type="number" name="precipitation_threshold" step="0.1" min="0"
                                     value="{{ old('precipitation_threshold', 10.0) }}" class="mt-1 block w-full" required />
                            <x-input-error for="precipitation_threshold" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <x-button>
                                {{ __('Create Alert') }}
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
