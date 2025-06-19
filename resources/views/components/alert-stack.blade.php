<div class="fixed z-50 space-y-3 top-4 right-4 min-w-[300px]">
    @foreach (['success', 'error', 'info', 'warning'] as $type)
        @if (session()->has($type))
            <x-alert :type="$type" :message="session($type)" position="top-right" />
        @endif
    @endforeach
</div>
