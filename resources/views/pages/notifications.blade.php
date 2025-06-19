{{-- resources/views/search-results.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">
            Notifications
        </h1>
    </x-slot>
    <div class="py-8">
        @livewire('notification')
    </div>
</x-app-layout>
