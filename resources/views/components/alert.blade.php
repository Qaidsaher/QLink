@props([
    'type' => 'info', // success, error, warning, info
    'message',
    'position' => null, // e.g. 'top-right', 'bottom-left'
])

@php
    $colors = [
        'success' => 'green',
        'error' => 'red',
        'warning' => 'yellow',
        'info' => 'blue',
    ];
    $color = $colors[$type] ?? 'blue';

    $positionClasses = [
        'top-right'    => 'top-4 right-4',
        'top-left'     => 'top-4 left-4',
        'bottom-right' => 'bottom-4 right-4',
        'bottom-left'  => 'bottom-4 left-4',
        'top-center'   => 'top-4 left-1/2 transform -translate-x-1/2',
        'bottom-center'=> 'bottom-4 left-1/2 transform -translate-x-1/2',
        'center'       => 'top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2',
    ];

    $absolutePosition = $position ? 'absolute z-50 ' . ($positionClasses[$position] ?? '') : '';
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    x-init="setTimeout(() => show = false, 7000)"
    role="alert"
    class="{{ $absolutePosition }} flex items-center justify-between p-4 mb-3 text-sm
           text-{{ $color }}-700 bg-{{ $color }}-100 border border-{{ $color }}-300
           rounded-lg shadow dark:text-{{ $color }}-300 dark:bg-{{ $color }}-900/50 dark:border-{{ $color }}-700
           w-full max-w-full sm:max-w-lg"
>
    <div class="flex items-center space-x-2">
        <i class="fa-solid
            @if($type === 'success') fa-check-circle
            @elseif($type === 'error') fa-times-circle
            @elseif($type === 'warning') fa-exclamation-triangle
            @else fa-info-circle
            @endif
        "></i>
        <p class="font-medium break-words">{{ $message }}</p>
    </div>
    <button
        @click="show = false"
        class="ml-4 text-{{ $color }}-500 hover:text-{{ $color }}-800 dark:hover:text-white"
        aria-label="Dismiss"
    >
        <i class="fas fa-times"></i>
    </button>
</div>
