@props(['name'])

<div
    x-show="activeTab === '{{ $name }}'"
    x-transition:enter="transition-all ease-in-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    role="tabpanel"
    class="py-6 focus:outline-none"
>
    {{ $slot }}
</div>
