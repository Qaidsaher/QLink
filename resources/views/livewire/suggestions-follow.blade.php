<div class="p-5 shadow bg-slate-100 dark:bg-slate-900 rounded-2xl">
    <h3 class="mb-4 text-xl font-bold text-slate-800 dark:text-slate-100">Who to follow</h3>
    <div class="space-y-4">
        @if(auth()->check()) {{-- Only show content if user is logged in --}}
            @forelse ($suggestions as $suggestion)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    {{-- Assuming you have a route for user profiles --}}
                    <a href="{{ route('profile.show', $suggestion->username) }}"> {{-- Adjust route name as needed --}}
                       <x-avatar :user="$suggestion" />
                    </a>
                    <div class="leading-tight">
                        <a href="{{ route('profile.show', $suggestion->username) }}" class="font-bold text-slate-700 dark:text-slate-200 hover:underline hover:text-blue-600 dark:hover:text-blue-400">
                            {{ $suggestion->name }}
                        </a>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            @<span>{{ $suggestion->username }}</span>
                        </p>
                    </div>
                </div>

                {{-- The button's state is now implicitly handled because followed users are excluded by the query --}}
                {{-- If a user is in the $suggestions list, they are NOT followed by auth()->user() --}}
                <button
                    wire:click="toggleFollow({{ $suggestion->id }})"
                    wire:loading.attr="disabled"
                    wire:target="toggleFollow({{ $suggestion->id }})"
                    class="px-4 py-1.5 text-sm font-semibold bg-slate-800 text-white dark:bg-slate-200 dark:text-black rounded-full hover:opacity-90 focus:outline-none transition-colors duration-150 ease-in-out">
                    <span wire:loading wire:target="toggleFollow({{ $suggestion->id }})" class="inline-block w-3 h-3 mr-1 border-2 border-current rounded-full animate-spin border-t-transparent" role="status" aria-hidden="true"></span>
                    Follow
                </button>
                {{--
                    If you wanted a "Following" button for users you *are* following (e.g., in a different context),
                    you would check: auth()->user()->isFollowing($suggestion)
                --}}
            </div>
            @empty
            <p class="text-sm text-slate-500 dark:text-slate-400">No new suggestions right now.</p>
            @endforelse

            @if ($hasMoreSuggestions && $suggestions->isNotEmpty())
            <button wire:click="loadMore" wire:loading.attr="disabled"
                    class="block w-full pt-2 text-sm text-center text-blue-600 dark:text-blue-400 hover:underline focus:outline-none">
                <span wire:loading wire:target="loadMore" class="inline-block w-3 h-3 mr-1 border-2 border-current rounded-full animate-spin border-t-transparent" role="status" aria-hidden="true"></span>
                Show more
            </button>
            @endif
        @else
            <p class="text-sm text-slate-500 dark:text-slate-400">Login to see suggestions.</p>
        @endif
    </div>
</div>
