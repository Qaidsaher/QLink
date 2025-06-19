{{-- resources/views/search-results.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">
            Search
            @if(request()->filled('q'))
                Results for: "<span class="text-blue-500">{{ Str::limit(request('q'), 30) }}</span>"
            @endif
        </h1>
    </x-slot>

    <div>
        @livewire('search')
    </div>
</x-app-layout>
