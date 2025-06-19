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
                } else {
                    // Livewire's updatedQuery will set showResults if there are results
                }
            });
            this.$watch('showResults', value => {
                if(value) {
                    this.resultsCount = this.$refs.resultsList ? this.$refs.resultsList.children.length : 0;
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
    class="relative w-full max-w-md mx-auto" {{-- Adjust width as needed --}}
    role="search">

    {{-- Search Input --}}
    <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
        </div>
        <input
            wire:model.live.debounce.300ms="query"
            x-ref="searchInput"
            @focus="if (query.length >= 2) showResults = true"
            @keydown="navigateResults"
            type="search"
            name="search"
            id="globalSearchInput"
            autocomplete="off"
            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-full leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 sm:text-sm shadow-sm"
            placeholder="Search users or posts...">

        <div x-show="query.length > 0" class="absolute inset-y-0 right-0 flex items-center pr-3">
            <button @click="query = ''; $wire.resetResults(); $refs.searchInput.focus();" type="button" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                <span class="sr-only">Clear search</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 101.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Results Dropdown --}}
    <div x-show="showResults && query.length >= 2"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-30 w-full mt-1 overflow-y-auto bg-white border border-gray-200 rounded-md shadow-2xl dark:bg-gray-800 max-h-96 dark:border-gray-700"
         style="display: none;" {{-- X-cloak is also good here for initial load --}}
         >
        <ul x-ref="resultsList" class="divide-y divide-gray-100 dark:divide-gray-700" role="listbox">
            {{-- Users Section --}}
            @if($users->isNotEmpty())
                <li class="px-4 py-2 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400">
                    Users
                </li>
                @foreach($users as $userResult)
                    <li wire:key="user-{{ $userResult->id }}" role="option" :aria-selected="selectedResult === ({{ $loop->index }} + 0)"> {{-- Adjust index if other sections are before users --}}
                        <a href="{{ route('profile.show', ['user' => $userResult->username]) }}" {{-- Assuming 'profile.show' takes username --}}
                           class="block px-4 py-3 text-sm text-gray-700 transition-colors duration-150 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-100 dark:focus:bg-gray-700 focus:outline-none">
                            <div class="flex items-center">
                                <img src="{{ $userResult->avatar_url }}" alt="{{ $userResult->name }}" class="object-cover w-8 h-8 mr-3 rounded-full">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $userResult->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">@<span>{{ $userResult->username }}</span></p>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            @endif

            {{-- Posts Section --}}
            @if($posts->isNotEmpty())
                <li class="px-4 py-2 text-xs font-semibold tracking-wider text-gray-500 uppercase border-t border-gray-100 dark:text-gray-400 dark:border-gray-700">
                    Posts
                </li>
                @foreach($posts as $postResult)
                    <li wire:key="post-{{ $postResult->id }}" role="option" :aria-selected="selectedResult === ({{ $loop->index }} + {{ $users->count() }})">
                        <a href="#" {{-- Link to individual post page: route('posts.show', $postResult->id) --}}
                           class="block px-4 py-3 text-sm text-gray-700 transition-colors duration-150 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-100 dark:focus:bg-gray-700 focus:outline-none">
                            <div class="flex items-start space-x-2.5">
                                <img src="{{ $postResult->user->avatarUrl() }}" alt="{{ $postResult->user->name }}" class="h-8 w-8 rounded-full object-cover flex-shrink-0 mt-0.5">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-medium text-gray-800 truncate dark:text-gray-100">{{ $postResult->user->name }}</span>
                                        <span class="text-gray-400 dark:text-gray-500">{{ $postResult->created_at->diffForHumans(null, true, true) }}</span> {{-- short diff --}}
                                    </div>
                                    <p class="mt-0.5 text-gray-600 dark:text-gray-300 text-sm leading-snug clamp-2">
                                        {{ Str::limit(strip_tags($postResult->content), 120) }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            @endif

            {{-- No Results Message --}}
            @if($query && strlen($query) >= 2 && $users->isEmpty() && $posts->isEmpty() && $showResults)
                <li class="px-4 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                    No results found for "<strong class="text-gray-700 dark:text-gray-200">{{ $query }}</strong>".
                </li>
            @endif

            {{-- View All Results Link --}}
            @if(($users->isNotEmpty() || $posts->isNotEmpty()) && strlen($query) >=2)
                <li class="border-t border-gray-100 dark:border-gray-700">
                    <button wire:click="viewAllResults" type="button"
                       class="block w-full px-4 py-3 text-sm font-medium text-left text-indigo-600 transition-colors duration-150 dark:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-100 dark:focus:bg-gray-700 focus:outline-none">
                        View all results for "<strong class="text-indigo-700 dark:text-indigo-300">{{ $query }}</strong>"
                    </button>
                </li>
            @endif
        </ul>
    </div>
    {{-- For a simple "Loading..." indicator when Livewire is fetching: --}}
    <div wire:loading wire:target="query" class="absolute z-30 w-full p-4 mt-1 text-sm text-center text-gray-500 bg-white border border-gray-200 rounded-md shadow-lg dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700">
        Searching...
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
