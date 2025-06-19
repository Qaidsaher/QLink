{{--
    This component now expects a named 'icon' slot for the icon,
    and the default slot for the text/label content.
--}}
@props(['active' => false, 'href' => '#'])

@php
$baseClasses = 'flex items-center w-full p-3 rounded-lg font-semibold text-base transition-colors duration-200 group';
$activeClasses = 'bg-blue-500/10 text-blue-500';
$inactiveClasses = 'text-slate-500 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200';

$classes = $baseClasses . ' ' . ($active ? $activeClasses : $inactiveClasses);
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} wire:navigate :key="'href-' + '{{ $href }}'">
    
    {{-- Render the 'icon' slot here --}}
    <div class="flex-shrink-0" :class="{ 'mr-4': isSidebarOpen, 'mx-auto': !isSidebarOpen }">
        {{ $icon }}
    </div>

    {{-- Render the default slot (text) here, with Alpine show/hide logic --}}
    <div class="flex-1"
        x-show="isSidebarOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        {{ $slot }}
    </div>
</a>
