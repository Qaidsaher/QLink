@props(['name'])

@php
$isActive = 'activeTab === \'' . $name . '\'';

$inactiveClasses = 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600';
$activeClasses = 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400';
@endphp

<button
    @click="activeTab = '{{ $name }}'"
    :class="{ '{{ $activeClasses }}': {{ $isActive }}, '{{ $inactiveClasses }}': !({{ $isActive }}) }"
    role="tab"
    class="inline-flex items-center justify-center px-1 py-2 text-sm font-medium transition-colors duration-150 border-b-2 group shrink-0"
>
    {{ $slot }}
</button>
