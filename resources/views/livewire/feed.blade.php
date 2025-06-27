<div>
    {{--
    This is the main view for your home feed.
    It includes a sticky header and then renders the components
    for creating and viewing posts.
    --}}

    {{-- Sticky Header with Tabs for the Home Feed --}}
    <header
        class="sticky top-0 z-20 border-b border-gray-200 bg-white/80 dark:bg-black/80 backdrop-blur-sm dark:border-slate-700">
        <div class="px-4 py-4">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Home</h1>
        </div>

        {{-- "For you" and "Following" Tabs --}}
        {{-- <nav class="flex justify-around" aria-label="Tabs">
            <a href="#"
                class="w-full py-4 text-sm font-semibold text-center transition-colors border-b-2 border-blue-500 text-slate-900 dark:text-white">
                For you
            </a>
            <a href="#"
                class="w-full py-4 text-sm font-semibold text-center transition-colors border-b-2 border-transparent text-slate-500 hover:bg-gray-200 dark:text-slate-400 dark:hover:bg-slate-800">
                Following
            </a>
        </nav> --}}
    </header>

    {{-- Post Composer --}}
    <div class="border-b border-gray-200 dark:border-slate-700">
        <livewire:post-create wire:key="post-create" />
    </div>
    <livewire:posts wire:key="post-feed" />
</div>