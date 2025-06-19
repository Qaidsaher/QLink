<div>
    {{-- Main container for the search component's content --}}
    <div class="container px-4 py-8 mx-auto sm:px-6 lg:px-8">

        {{-- Search Input Bar --}}
        <div class="mb-8">
            <div class="relative max-w-2xl mx-auto">
                <input
                    wire:model.live.debounce.500ms="query"
                    type="search"
                    placeholder="Search for users, posts, or #hashtags..."
                    aria-label="Search"
                    class="w-full px-4 py-3 pr-12 text-sm text-gray-800 bg-white border border-gray-300 rounded-full shadow-sm dark:text-slate-100 dark:bg-slate-700 dark:border-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent"
                />
                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                    <svg wire:loading wire:target="query" class="w-5 h-5 text-gray-400 animate-spin dark:text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg wire:loading.remove wire:target="query" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Header: Search Status --}}
        <header class="mb-6 text-center sm:text-left">
            @if (!empty($query))
                <h1 class="mb-1 text-2xl font-bold text-gray-800 sm:text-3xl dark:text-slate-100">
                    Results for: "<span class="text-blue-600 dark:text-blue-400">{{ Str::limit($query, 50) }}</span>"
                </h1>
                {{-- Only show count if there's a query and some results OR if specific collections have items --}}
                @if ($totalResultsCount > 0 || $usersResults->isNotEmpty() || $postsResults->isNotEmpty() || $hashtagsResults->isNotEmpty())
                    <p class="text-sm text-gray-600 dark:text-slate-400">
                        Found {{ $totalResultsCount }} {{ Str::plural('result', $totalResultsCount) }} for your search.
                    </p>
                @endif
            @else
                <h1 class="mb-2 text-2xl font-bold text-gray-800 sm:text-3xl dark:text-slate-100">
                    Search The Platform
                </h1>
                <p class="text-gray-600 dark:text-slate-400">Enter a term above to find users, posts, or topics.</p>
            @endif
        </header>

        {{-- Main Content Area: Tabs and Results (only if query is present) --}}
        @if (!empty($query))
            {{-- Filter Tabs --}}
            <div class="mb-6 border-b border-gray-200 dark:border-slate-700">
                <nav class="flex flex-wrap -mb-px sm:space-x-6" aria-label="Tabs">
                    <button wire:click="setFilterType(null)"
                        class="whitespace-nowrap py-3 px-2 sm:px-1 border-b-2 font-medium text-sm transition-colors duration-150 ease-in-out {{ is_null($filterType) ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-300' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:border-slate-600' }}">
                        All
                    </button>
                    <button wire:click="setFilterType('users')" @if($usersResults->total() === 0 && !is_null($filterType) && $filterType !== 'users') disabled @endif
                        class="whitespace-nowrap py-3 px-2 sm:px-1 border-b-2 font-medium text-sm transition-colors duration-150 ease-in-out {{ $filterType === 'users' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-300' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:border-slate-600 disabled:opacity-50 disabled:cursor-not-allowed' }}">
                        Users <span class="hidden sm:inline text-xs {{ $filterType === 'users' ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 dark:text-slate-500' }}">({{ $usersResults->total() }})</span>
                    </button>
                    <button wire:click="setFilterType('posts')" @if($postsResults->total() === 0 && !is_null($filterType) && $filterType !== 'posts') disabled @endif
                        class="whitespace-nowrap py-3 px-2 sm:px-1 border-b-2 font-medium text-sm transition-colors duration-150 ease-in-out {{ $filterType === 'posts' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-300' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:border-slate-600 disabled:opacity-50 disabled:cursor-not-allowed' }}">
                        Posts <span class="hidden sm:inline text-xs {{ $filterType === 'posts' ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 dark:text-slate-500' }}">({{ $postsResults->total() }})</span>
                    </button>
                    <button wire:click="setFilterType('hashtags')" @if($hashtagsResults->total() === 0 && !is_null($filterType) && $filterType !== 'hashtags') disabled @endif
                        class="whitespace-nowrap py-3 px-2 sm:px-1 border-b-2 font-medium text-sm transition-colors duration-150 ease-in-out {{ $filterType === 'hashtags' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-300' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:border-slate-600 disabled:opacity-50 disabled:cursor-not-allowed' }}">
                        Hashtags <span class="hidden sm:inline text-xs {{ $filterType === 'hashtags' ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 dark:text-slate-500' }}">({{ $hashtagsResults->total() }})</span>
                    </button>
                </nav>
            </div>

            {{-- Results Sections with Loading State --}}
            <div wire:loading.delay.class="transition-opacity opacity-50" wire:target="query, setFilterType">
                {{-- User Results --}}
                @if(is_null($filterType) || $filterType === 'users')
                    @if($usersResults->isNotEmpty())
                        <section class="mb-10">
                            <h2 class="mb-4 text-xl font-semibold text-gray-700 dark:text-slate-200">Users</h2>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($usersResults as $user)
                                    <div class="p-5 transition-shadow duration-200 bg-white rounded-lg shadow-md dark:bg-slate-800 hover:shadow-lg">
                                        <div class="flex items-center mb-3">
                                            <a href="{{ route('profile.show', $user->username) }}">
                                                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="object-cover w-12 h-12 mr-4 rounded-full">
                                            </a>
                                            <div>
                                                <a href="{{ route('profile.show', $user->username) }}" class="text-lg font-semibold text-gray-800 dark:text-slate-100 hover:text-blue-600 dark:hover:text-blue-400">{{ $user->name }}</a>
                                                <p class="text-sm text-gray-500 dark:text-slate-400">@<span>{{ $user->username }}</span></p>
                                            </div>
                                        </div>
                                        @if($user->bio)
                                        <p class="mb-3 text-sm text-gray-600 dark:text-slate-300 line-clamp-2">{{ $user->bio }}</p>
                                        @endif
                                        <a href="{{ route('profile.show', $user->username) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View Profile</a>
                                    </div>
                                @endforeach
                            </div>
                            @if ($usersResults->hasPages())
                                <div class="mt-6">
                                    {{ $usersResults->links() }}
                                </div>
                            @endif
                        </section>
                    @elseif($filterType === 'users' && !empty($query)) {{-- Show 'no users' only if 'users' filter is active --}}
                        <p class="py-4 text-gray-600 dark:text-slate-400">No users found for "<span class="font-semibold">{{ $query }}</span>".</p>
                    @endif
                @endif

                {{-- Post Results --}}
                @if(is_null($filterType) || $filterType === 'posts')
                     @if($postsResults->isNotEmpty())
                        <section class="mb-10">
                            <h2 class="mb-4 text-xl font-semibold text-gray-700 dark:text-slate-200">Posts</h2>
                            <div class="space-y-6">
                                @foreach ($postsResults as $post)
                                    <article class="p-5 transition-shadow duration-200 bg-white rounded-lg shadow-md dark:bg-slate-800 hover:shadow-lg">
                                        <div class="flex items-center mb-3">
                                            <a href="{{ route('profile.show', $post->user->username) }}">
                                                <img src="{{ $post->user->avatarUrl() }}" alt="{{ $post->user->name }}" class="object-cover w-10 h-10 mr-3 rounded-full">
                                            </a>
                                            <div>
                                                <a href="{{ route('profile.show', $post->user->username) }}" class="font-semibold text-gray-800 dark:text-slate-100 hover:text-blue-600 dark:hover:text-blue-400">{{ $post->user->name }}</a>
                                                <p class="text-xs text-gray-500 dark:text-slate-400">
                                                    <a href="{{ route('posts.show', $post->id) }}">{{ $post->created_at->format('M d, Y \a\t h:i A') }}</a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mb-3 text-sm prose text-gray-700 max-w-none dark:prose-invert dark:text-slate-300">
                                            {!! Illuminate\Support\Str::markdown(Str::limit(strip_tags($post->content), 250)) !!}
                                        </div>
                                        @if($post->attachments->where('file_type', 'image')->isNotEmpty())
                                            <div class="grid grid-cols-2 gap-2 mt-3 sm:grid-cols-3">
                                                @foreach($post->attachments->where('file_type', 'image')->take(3) as $attachment)
                                                    <a href="{{ route('posts.show', $post->id) }}">
                                                        <img src="{{ $attachment->file_url_thumbnail ?? $attachment->file_url }}" alt="Post image" class="object-cover w-full rounded-md aspect-video sm:aspect-square">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                        <div class="mt-4">
                                            <a href="{{ route('posts.show', $post->id) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Read more →</a>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                            @if ($postsResults->hasPages())
                                <div class="mt-6">
                                    {{ $postsResults->links() }}
                                </div>
                            @endif
                        </section>
                    @elseif($filterType === 'posts' && !empty($query)) {{-- Show 'no posts' only if 'posts' filter is active --}}
                        <p class="py-4 text-gray-600 dark:text-slate-400">No posts found for "<span class="font-semibold">{{ $query }}</span>".</p>
                    @endif
                @endif

                {{-- Hashtag Results --}}
                @if(is_null($filterType) || $filterType === 'hashtags')
                    @if($hashtagsResults->isNotEmpty())
                        <section class="mb-10">
                            <h2 class="mb-4 text-xl font-semibold text-gray-700 dark:text-slate-200">Hashtags</h2>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($hashtagsResults as $hashtag)
                                    <a href="{{ $hashtag->url }}" class="px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-700 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-600 transition-colors">
                                        {{ $hashtag->name }} <span class="text-xs opacity-75">({{ $hashtag->posts_count }})</span>
                                    </a>
                                @endforeach
                            </div>
                             @if ($hashtagsResults->hasPages())
                                <div class="mt-6">
                                    {{ $hashtagsResults->links() }}
                                </div>
                            @endif
                        </section>
                    @elseif($filterType === 'hashtags' && !empty($query)) {{-- Show 'no hashtags' only if 'hashtags' filter is active --}}
                         <p class="py-4 text-gray-600 dark:text-slate-400">No hashtags found for "<span class="font-semibold">{{ $query }}</span>".</p>
                    @endif
                @endif

                {{-- Overall "No Results" message if applicable --}}
                @php
                    $noUsersMatch = (is_null($filterType) || $filterType === 'users') && $usersResults->isEmpty();
                    $noPostsMatch = (is_null($filterType) || $filterType === 'posts') && $postsResults->isEmpty();
                    $noHashtagsMatch = (is_null($filterType) || $filterType === 'hashtags') && $hashtagsResults->isEmpty();
                    $showOverallNoResultsMessage = $noUsersMatch && $noPostsMatch && $noHashtagsMatch;
                @endphp
                @if (!empty($query) && $showOverallNoResultsMessage)
                    <div class="py-10 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-gray-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-slate-200">No results found</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">We couldn't find anything for "<span class="font-semibold">{{ Str::limit($query, 30) }}</span>". Try a different search term.</p>
                    </div>
                @endif
            </div> {{-- End wire:loading --}}
        @elseif(empty($query) && request()->filled('q'))
            {{-- This handles the edge case where URL has 'q' but component's $query is empty somehow initially --}}
             <div class="py-10 text-center">
                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Loading search results or enter a term above to begin.</p>
            </div>
        @endif
    </div> {{-- End container --}}
</div>
