<aside class="sticky top-0 flex-col hidden h-screen max-w-[384px] p-4 space-y-4 lg:flex xl:w-96 shrink-0">
    @livewire('global-search')
    @livewire('trending-topics')
    @livewire('suggestions-follow')
    <div class="p-2 mt-auto text-xs text-slate-500">
        <div class="flex flex-wrap gap-x-3">
            <a href="{{ route('about') }}" wire:navigate class="hover:underline">About</a>
            <a href="{{ route('terms') }}" wire:navigate class="hover:underline">Terms of Service</a>
            <a href="{{ route('privacy') }}" wire:navigate class="hover:underline">Privacy Policy</a>
        </div>
        <div class="p-2">
            @livewire('theme-toggle')
        </div>
        <p class="mt-2">© {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</aside>