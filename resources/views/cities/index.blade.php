<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Cities') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Search Form -->
                <div class="p-6 border-b">
                    <form method="GET" action="{{ route('cities.index') }}" class="flex gap-4">
                        <x-input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by city or country..."
                            class="block w-full"
                        />
                        <x-button type="submit">
                            {{ __('Search') }}
                        </x-button>
                        @if(request('search'))
                            <a href="{{ route('cities.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                {{ __('Clear') }}
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Cities List -->
                <div class="bg-white divide-y divide-gray-200">
                    @forelse($cities as $city)
                        <div class="p-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ $city->name }}
                                </h3>
                                <div class="mt-1 text-sm text-gray-500">
                                    <p>{{ $city->country }}</p>
                                    <p class="mt-1">
                                        Coordinates: {{ $city->latitude }}, {{ $city->longitude }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('weather.alerts.create', ['city_id' => $city->id]) }}"
                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    {{ __('Create Alert') }}
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            {{ request('search')
                                ? __('No cities found matching your search criteria.')
                                : __('No cities available.')
                            }}
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($cities->hasPages())
                    <div class="p-6 bg-white border-t">
                        {{ $cities->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
