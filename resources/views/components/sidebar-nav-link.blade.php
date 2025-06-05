@props(['active'])

@php
$classes = ($active ?? false)
            ? 'group flex items-center px-3 py-2.5 text-sm font-medium rounded-full text-primary-DEFAULT bg-primary-DEFAULT/10 dark:text-primary-light dark:bg-primary-dark/20'
            : 'group flex items-center px-3 py-2.5 text-sm font-medium rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
