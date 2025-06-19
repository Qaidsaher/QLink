<div class="p-5 shadow bg-slate-100 dark:bg-slate-900 rounded-2xl">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Trends for you</h3>
        {{-- Optional: Time period switcher --}}
        <div class="text-xs">
            <select wire:model.live="timePeriod" class="px-2 py-1 text-xs rounded-md border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="week">Past Week</option>
                <option value="day">Past Day</option>
                <option value="month">Past Month</option>
                <option value="all">All Time</option>
            </select>
        </div>
    </div>

    <div wire:loading.class="opacity-50" class="space-y-3">
        @if ($trends->isEmpty() && !$this->allFetchedTrends?->isEmpty() && $this->displayCount > $this->initialCount)
            {{-- This case means we tried to load more but there were no more unique trends than already shown --}}
             <p class="py-2 text-sm text-center text-slate-500 dark:text-slate-400">No more trends to show.</p>
        @elseif ($trends->isEmpty() && $this->allFetchedTrends?->isEmpty())
             <div wire:loading.remove>
                <p class="py-2 text-sm text-center text-slate-500 dark:text-slate-400">No trends available right now.</p>
            </div>
        @endif

        {{-- Loading indicator for initial load or period change --}}
        <div wire:loading wire:target="loadTrends, setTimePeriod" class="flex items-center justify-center w-full py-4">
            <svg class="w-6 h-6 text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>


        @foreach ($trends as $trend)
        <div class="group">
            <a href="{{ $trend['url'] }}" class="text-sm font-bold text-slate-700 dark:text-slate-200 hover:underline hover:text-blue-600 dark:hover:text-blue-400">
                {{ $trend['name'] }}
            </a>
            <p class="text-xs text-slate-500 dark:text-slate-400">
                {{ $this->formatPostCount($trend['posts_count']) }} Posts
            </p>
        </div>
        @endforeach

        @if ($hasMoreTrends && $trends->isNotEmpty())
        <button wire:click="loadMore" wire:loading.attr="disabled"
                class="block w-full pt-2 text-sm text-center text-blue-600 dark:text-blue-400 hover:underline focus:outline-none">
            <span wire:loading wire:target="loadMore" class="inline-block w-3 h-3 mr-1 border-2 border-current rounded-full animate-spin border-t-transparent" role="status" aria-hidden="true"></span>
            Show more
        </button>
        @endif
    </div>
</div>
