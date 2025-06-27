<div x-data="{
        showResults: @entangle('showResults').live,
        query: @entangle('query').live,
        selectedResult: -1,
        resultsCount: 0,
        init() {
            this.$watch('query', value => {
                if (value.length < 2) {
                    this.showResults = false;
                    this.selectedResult = -1;
                }
            });
            this.$watch('showResults', value => {
                if (value) {
                    this.resultsCount = this.$refs.resultsList ? Array.from(this.$refs.resultsList.children).filter(el => el.tagName === 'LI' && el.querySelector('a')).length : 0;
                } else {
                    this.selectedResult = -1;
                }
            });
        },
        closeResults() {
            this.showResults = false;
            this.selectedResult = -1;
        },
        navigateResults(event) {
            if (!this.showResults || this.resultsCount === 0) return;
            const items = Array.from(this.$refs.resultsList.children).filter(el => el.tagName === 'LI' && el.querySelector('a'));
            if (items.length === 0) return;

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                this.selectedResult = (this.selectedResult + 1) % items.length;
                items[this.selectedResult].querySelector('a').focus();
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                this.selectedResult = (this.selectedResult - 1 + items.length) % items.length;
                items[this.selectedResult].querySelector('a').focus();
            } else if (event.key === 'Enter' && this.selectedResult > -1) {
                event.preventDefault();
                items[this.selectedResult].querySelector('a').click();
            } else if (event.key === 'Escape') {
                this.closeResults();
                this.$refs.searchInput.blur();
            }
        }
    }"
    @click.away="closeResults()"
    class="relative w-full"
    role="search">

    {{-- Search Input (X.com style) --}}
    <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
        </div>
        <input
            wire:model.live.debounce.300ms="query"
            x-ref="searchInput"
            @focus="if (query.length >= 2) showResults = true"
            @keydown="navigateResults"
            type="search"
            autocomplete="off"
            class="block w-full py-2.5 pl-10 pr-10 text-sm text-gray-900 placeholder-gray-500 bg-gray-100 border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white dark:bg-slate-900 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:bg-slate-900"
            placeholder="Search">

        <div x-show="query.length > 0" x-transition class="absolute inset-y-0 right-0 flex items-center pr-2">
            <button @click="query = ''; $wire.resetResults(); $refs.searchInput.focus();" type="button" class="flex items-center justify-center w-5 h-5 text-gray-500 bg-blue-500 rounded-full hover:bg-blue-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>

    {{-- Results Dropdown --}}
    <div x-show="showResults && query.length >= 2"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute z-30 w-full mt-2 overflow-hidden bg-white border border-gray-200 shadow-lg rounded-2xl dark:bg-slate-900 dark:border-slate-700"
         style="display: none;">

        <div wire:loading wire:target="query" class="w-full p-4 text-sm text-center text-gray-500 dark:text-gray-400">
            Searching...
        </div>

        <ul x-ref="resultsList" wire:loading.remove wire:target="query" role="listbox">

            {{-- Unified List: "Search for..." Link --}}
            <li wire:key="search-for-query" role="option">
                <a href="{{-- route('search.view', ['query' => $query]) --}}"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-700 transition-colors dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-800 focus:bg-gray-100 dark:focus:bg-slate-800 focus:outline-none">
                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    Search for "<span class="font-bold">{{ $query }}</span>"
                </a>
            </li>

            {{-- Users Results --}}
            @foreach($users as $userResult)
                <li wire:key="user-{{ $userResult->id }}" role="option" :aria-selected="selectedResult === ({{ $loop->index }} + 1)">
                    <a href="{{ route('profile.show', ['user' => $userResult->username]) }}" wire:navigate
                       class="block px-4 py-3 text-sm transition-colors dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-800 focus:bg-gray-100 dark:focus:bg-slate-800 focus:outline-none">
                        <div class="flex items-center">
                            <img src="{{ $userResult->avatar_url }}" alt="{{ $userResult->name }}" class="object-cover w-10 h-10 mr-3 rounded-full">
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white">{{ $userResult->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">@<span>{{ $userResult->username }}</span></p>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach

            {{-- Posts Results --}}
            @foreach($posts as $postResult)
                <li wire:key="post-{{ $postResult->id }}" role="option" :aria-selected="selectedResult === ({{ $loop->index }} + {{ $users->count() }} + 1)">
                    <a href="{{ route('posts.show', $postResult->id) }}" wire:navigate
                       class="block px-4 py-3 text-sm transition-colors dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-800 focus:bg-gray-100 dark:focus:bg-slate-800 focus:outline-none">
                        <div class="flex items-start space-x-3">
                            <img src="{{ $postResult->user->avatarUrl() }}" alt="{{ $postResult->user->name }}" class="flex-shrink-0 object-cover w-8 h-8 rounded-full">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-baseline space-x-2 text-xs">
                                    <p class="font-bold text-gray-900 truncate dark:text-white">{{ $postResult->user->name }}</p>
                                    <p class="text-gray-500 truncate dark:text-gray-400">@<span>{{ $postResult->user->username }}</span></p>
                                    <p class="text-gray-500 dark:text-gray-400">&middot;</p>
                                    <p class="flex-shrink-0 text-gray-500 dark:text-gray-400">{{ $postResult->created_at->diffForHumans(null, true) }}</p>
                                </div>
                                <p class="mt-1 text-gray-700 dark:text-gray-300 clamp-2">{{ $postResult->content }}</p>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach

            {{-- No Results Message --}}
            @if($query && strlen($query) >= 2 && $users->isEmpty() && $posts->isEmpty() && $showResults)
                <li class="px-4 py-10 text-sm text-center text-gray-500 dark:text-gray-400">
                    <p class="font-bold">No results for "{{ $query }}"</p>
                    <p class="mt-1">Try searching for something else.</p>
                </li>
            @endif
        </ul>
    </div>
</div>

@push('styles')
<style>
    .clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush
