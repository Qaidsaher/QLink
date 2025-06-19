@props(['user', 'size' => '10', 'class' => ''])

<div
    {{ $attributes->merge(['class' => 'relative block user-avatar-container '.$class]) }}
    data-user-id="{{ $user->id }}"
>
    <img
        class="h-{{ $size }} w-{{ $size }} rounded-full border"
        src="{{ $user->avatarUrl() }}"
        alt="{{ $user->name }}"
    >

    <span
        class="absolute bottom-0 right-0 hidden block w-3 h-3 bg-green-500 rounded-full avatar-status-dot online-dot ring-1 ring-white"
        title="Online"
    ></span>

    <span
        class="absolute bottom-0 right-0 block w-3 h-3 bg-gray-400 rounded-full avatar-status-dot offline-dot ring-1 ring-white"
        title="Offline"
    ></span>
</div>
