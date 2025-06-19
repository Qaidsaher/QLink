@props(['defaultTab'])

<div x-data="{ activeTab: @entangle($attributes->wire('model')) }"
     {{ $attributes->whereDoesntStartWith('wire:model') }}>
    {{ $slot }}
</div>
