<x-app-layout>

    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Profile </h1>
    </x-slot>
    @livewire('user-profile', ['user' => $user])
</x-app-layout>
