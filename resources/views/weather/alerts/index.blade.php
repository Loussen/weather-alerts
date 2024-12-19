<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Weather Alerts') }}
            </h2>
            <a href="{{ route('weather.alerts.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                {{ __('New Alert') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($alerts->isEmpty())
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <p class="text-gray-500 text-center">
                        {{ __('No weather alerts set up yet.') }}
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($alerts as $alert)
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">
                                            {{ $alert->city->name }}, {{ $alert->city->country }}
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            @if($alert->isPaused())
                                                Paused until {{ $alert->paused_until->format('M d, H:i') }}
                                            @else
                                                {{ $alert->is_active ? 'Active' : 'Inactive' }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <x-dropdown>
                                            <x-slot name="trigger">
                                                <button class="text-gray-400 hover:text-gray-500">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                    </svg>
                                                </button>
                                            </x-slot>
                                            <x-slot name="content">
                                                <x-dropdown-link href="{{ route('weather.alerts.edit', $alert) }}">
                                                    {{ __('Edit') }}
                                                </x-dropdown-link>
                                                <form action="{{ route('weather.alerts.destroy', $alert) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                                        {{ __('Delete') }}
                                                    </x-dropdown-link>
                                                </form>
                                            </x-slot>
                                        </x-dropdown>
                                    </div>
                                </div>
                                <div class="mt-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">UV Index Threshold:</span>
                                        <span class="font-medium">{{ $alert->uv_threshold }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Precipitation Threshold:</span>
                                        <span class="font-medium">{{ $alert->precipitation_threshold }}mm/h</span>
                                    </div>
                                </div>
                                @unless($alert->isPaused())
                                    <div class="mt-4">
                                        <x-dropdown>
                                            <x-slot name="trigger">
                                                <button class="text-sm text-indigo-600 hover:text-indigo-700">
                                                    {{ __('Pause Alerts') }}
                                                </button>
                                            </x-slot>
                                            <x-slot name="content">
                                                @foreach([1, 3, 6, 12, 24] as $hours)
                                                    <form action="{{ route('weather.alerts.pause', $alert) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="hours" value="{{ $hours }}">
                                                        <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                                            {{ __('For') }} {{ $hours }} {{ Str::plural('hour', $hours) }}
                                                        </x-dropdown-link>
                                                    </form>
                                                @endforeach
                                            </x-slot>
                                        </x-dropdown>
                                    </div>
                                @endunless
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
