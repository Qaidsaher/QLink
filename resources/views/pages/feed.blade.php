<x-app-layout>
   
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Home</h1>
    </x-slot>
     @livewire('post-feed') {{-- This will render the PostFeed component --}}
    {{-- <div class="p-4 md:p-6">
        <div class="py-10 text-center text-gray-500">
            sdff
        </div>
    </div> --}}
</x-app-layout>
