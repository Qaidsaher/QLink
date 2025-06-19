{{-- Blade component --}}
<div
    role="tablist"
    {{ $attributes->merge(['class' => 'border-b border-gray-200 dark:border-gray-700']) }}
>
    <nav
        aria-label="Tabs"
        class="flex -mb-px space-x-4 overflow-x-auto tabs-scroll scroll-smooth"
    >
        {{ $slot }}
    </nav>
</div>
