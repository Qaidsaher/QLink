{{-- The main container, styled like an X.com sidebar module --}}
<div x-data="{ menuOpen: false }" class="relative p-4 bg-gray-100 dark:bg-slate-900 rounded-2xl">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-extrabold text-slate-900 dark:text-slate-100">Trends for you</h3>

        {{-- The settings cog icon button that toggles the dropdown --}}
        <button @click="menuOpen = !menuOpen"
            class="p-2 text-gray-500 rounded-full dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-slate-700"
            title="Settings">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.096 2.572-1.065z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </button>

        {{-- Dropdown Menu for Time Period Filter --}}
        <div x-show="menuOpen" @click.away="menuOpen = false" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute right-0 z-10 w-48 py-1 mt-12 bg-white border rounded-md shadow-xl dark:bg-slate-900 dark:border-slate-700"
            style="display: none;">

            <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Time Period</div>

            <button wire:click="setTimePeriod('day')" @click="menuOpen = false"
                class="flex items-center justify-between w-full px-3 py-2 text-sm text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-800">
                <span>Past Day</span>
                @if($timePeriod === 'day') <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg> @endif
            </button>
            <button wire:click="setTimePeriod('week')" @click="menuOpen = false"
                class="flex items-center justify-between w-full px-3 py-2 text-sm text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-800">
                <span>Past Week</span>
                @if($timePeriod === 'week') <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg> @endif
            </button>
            <button wire:click="setTimePeriod('month')" @click="menuOpen = false"
                class="flex items-center justify-between w-full px-3 py-2 text-sm text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-800">
                <span>Past Month</span>
                @if($timePeriod === 'month') <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg> @endif
            </button>
            <button wire:click="setTimePeriod('all')" @click="menuOpen = false"
                class="flex items-center justify-between w-full px-3 py-2 text-sm text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-800">
                <span>All Time</span>
                @if($timePeriod === 'all') <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg> @endif
            </button>
        </div>
    </div>

    {{-- This div handles the loading state opacity for the entire list --}}
    <div wire:loading.class.delay="opacity-50" class="space-y-4">

        {{-- Loading indicator for initial load or period change --}}
        <div wire:loading wire:target="loadTrends, setTimePeriod" class="flex items-center justify-center w-full py-4">
            <svg class="w-6 h-6 text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </div>

        {{-- List of Trends --}}
        <div wire:loading.remove wire:target="loadTrends, setTimePeriod"
            class="max-h-[200px] overflow-y-auto pr-2 space-y-4 scroll-smooth"
            >
            @forelse ($trends as $trend)
                <div wire:key="trend-{{ $loop->index }}" class="flex items-start justify-between group">
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Trending</p>
                        <a href="{{ $trend['url'] }}"
                            class="text-sm font-bold text-slate-800 dark:text-slate-200 hover:underline">
                            {{ $trend['name'] }}
                        </a>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $this->formatPostCount($trend['posts_count']) }} Posts
                        </p>
                    </div>
                    {{-- More options button, visible on hover --}}
                    <button
                        class="invisible p-2 text-gray-500 rounded-full dark:text-gray-400 group-hover:visible hover:bg-blue-100 hover:text-blue-500 dark:hover:bg-slate-700"
                        title="More options">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                            </path>
                        </svg>
                    </button>
                </div>
            @empty
                <div wire:loading.remove>
                    <p class="py-2 text-sm text-center text-slate-500 dark:text-slate-400">No trends available right now.
                    </p>
                </div>
            @endforelse

            {{-- "Show more" link --}}
            @if ($hasMoreTrends && $trends->isNotEmpty())
                <button wire:click="loadMore" wire:loading.attr="disabled"
                    class="block w-full pt-2 text-sm text-left text-blue-500 dark:text-blue-400 hover:underline focus:outline-none">
                    Show more
                </button>
            @endif
        </div>
    </div>
</div>