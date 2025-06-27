<div>
    {{-- Sticky Header with Search and Tabs --}}
    <header class="sticky top-0 z-20 border-b border-gray-200 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm dark:border-slate-700">
        <div class="container px-4 mx-auto sm:px-6 lg:px-8">
            {{-- Search Input Bar --}}
            <div class="py-4">
                <div class="relative max-w-2xl mx-auto">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                         <svg wire:loading.remove wire:target="query" class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                         <svg wire:loading wire:target="query" class="w-5 h-5 text-gray-400 animate-spin dark:text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                    <input
                        wire:model.live.debounce.500ms="query"
                        type="search"
                        placeholder="Search"
                        class="block w-full py-2.5 pl-12 pr-4 text-sm text-gray-900 placeholder-gray-500 bg-gray-100 border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white dark:bg-slate-800 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:bg-slate-900"
                    />
                </div>
            </div>

            {{-- Filter Tabs --}}
            @if (!empty($query))
            <nav class="flex justify-around" aria-label="Tabs">
                <button wire:click="setFilterType(null)" class="w-full py-3 text-sm font-semibold text-center transition-colors {{ is_null($filterType) ? 'border-b-2 border-blue-500 text-slate-900 dark:text-white' : 'text-slate-500 hover:bg-gray-200 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    Top
                </button>
                <button wire:click="setFilterType('posts')" @disabled($postsResults->total() === 0) class="w-full py-3 text-sm font-semibold text-center transition-colors {{ $filterType === 'posts' ? 'border-b-2 border-blue-500 text-slate-900 dark:text-white' : 'text-slate-500 hover:bg-gray-200 dark:text-slate-400 dark:hover:bg-slate-800' }} disabled:opacity-50">
                    Latest
                </button>
                <button wire:click="setFilterType('users')" @disabled($usersResults->total() === 0) class="w-full py-3 text-sm font-semibold text-center transition-colors {{ $filterType === 'users' ? 'border-b-2 border-blue-500 text-slate-900 dark:text-white' : 'text-slate-400 dark:hover:bg-slate-800' }} disabled:opacity-50">
                    People
                </button>
                <button wire:click="setFilterType('hashtags')" @disabled($hashtagsResults->total() === 0) class="w-full py-3 text-sm font-semibold text-center transition-colors {{ $filterType === 'hashtags' ? 'border-b-2 border-blue-500 text-slate-900 dark:text-white' : 'text-slate-500 hover:bg-gray-200 dark:text-slate-400 dark:hover:bg-slate-800' }} disabled:opacity-50">
                    Tags
                </button>
            </nav>
            @endif
        </header>
    </header>

    {{-- Main Content Area: Results --}}
    <div class="container px-0 mx-auto sm:px-0 lg:px-0">
        <div wire:loading.delay.class="opacity-50" wire:target="query, setFilterType" class="transition-opacity">

            {{-- Initial state before searching --}}
            @if(empty($query))
                <div class="p-10 text-center">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-slate-200">Try searching for people, posts, or keywords</h2>
                    <p class="text-sm text-gray-500 dark:text-slate-400">Find what’s happening on the platform.</p>
                </div>
            @else
                {{-- Unified Feed of Results --}}
                <div class="border-t border-gray-200 dark:border-slate-700">

                    {{-- User Results --}}
                    @if(is_null($filterType) || $filterType === 'users')
                        @foreach ($usersResults as $user)
                            <div wire:key="user-result-{{ $user->id }}" class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800/50">
                                <div class="flex items-center flex-1 min-w-0 gap-3">
                                    <a href="{{ route('profile.show', $user->username) }}" wire:navigate><x-avatar :user="$user" /></a>
                                    <div class="flex-1 min-w-0 leading-tight">
                                        <a href="{{ route('profile.show', $user->username) }}" wire:navigate class="font-bold text-slate-800 dark:text-slate-200 hover:underline">{{ $user->name }}</a>
                                        <p class="text-sm truncate text-slate-500 dark:text-slate-400">@<span>{{ $user->username }}</span></p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ml-2">
                                    {{-- You can add a follow button here if needed --}}
                                    <a href="{{ route('profile.show', $user->username) }}" wire:navigate class="px-4 py-1.5 text-sm font-bold bg-slate-900 text-white dark:bg-slate-50 dark:text-black rounded-full hover:opacity-90 transition-opacity">
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    {{-- Posts Results --}}
                    @if(is_null($filterType) || $filterType === 'posts')
                        @foreach ($postsResults as $post)
                             <div wire:key="post-result-{{ $post->id }}">
                                {{-- Include your standard post item view. This makes your UI consistent. --}}
                                @include('partials.posts._post-item', ['post' => $post])
                             </div>
                        @endforeach
                    @endif

                    {{-- Hashtag Results --}}
                    @if(is_null($filterType) || $filterType === 'hashtags')
                        @foreach ($hashtagsResults as $hashtag)
                            <div wire:key="hashtag-result-{{ $hashtag->name }}" class="p-4 border-b border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800/50">
                                <a href="{{ $hashtag->url }}" class="block">
                                    <p class="font-bold text-gray-900 dark:text-white">{{ $hashtag->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-slate-400">{{ $hashtag->posts_count }} {{ Str::plural('post', $hashtag->posts_count) }}</p>
                                </a>
                            </div>
                        @endforeach
                    @endif

                    {{-- No Results Message --}}
                    @if ($totalResultsCount === 0)
                        <div class="px-4 py-16 text-center">
                            <h2 class="text-xl font-bold text-gray-800 dark:text-slate-200">No results for "{{ Str::limit($query, 30) }}"</h2>
                            <p class="mt-2 text-sm text-gray-500 dark:text-slate-400">The term you entered did not bring up any results. You may have mistyped your term.</p>
                        </div>
                    @endif

                    {{-- Pagination: Styled to be less intrusive. Replace with a 'Load More' button for a true X.com feel. --}}
                    @if($usersResults->hasPages() && $filterType === 'users') <div class="p-4">{{ $usersResults->links() }}</div> @endif
                    @if($postsResults->hasPages() && $filterType === 'posts') <div class="p-4">{{ $postsResults->links() }}</div> @endif
                    @if($hashtagsResults->hasPages() && $filterType === 'hashtags') <div class="p-4">{{ $hashtagsResults->links() }}</div> @endif

                </div>
            @endif
        </div>
    </div>
</div>
