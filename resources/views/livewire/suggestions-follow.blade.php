{{-- The main container, styled like an X.com sidebar module --}}
<div class="p-4 bg-gray-100 dark:bg-slate-900 rounded-2xl">
    <h3 class="mb-4 text-xl font-extrabold text-slate-900 dark:text-slate-100">Who to follow</h3>

    <div class="max-h-[100px] overflow-y-auto pr-2 space-y-4 scroll-smooth"
   >
        {{-- Only show suggestions if the user is authenticated --}}
        @if(auth()->check())
            @forelse ($suggestions as $suggestion)
                <div wire:key="suggestion-{{ $suggestion->id }}" class="flex items-center justify-between">
                    {{-- User Info (Avatar, Name, Username) --}}
                    <div class="flex items-center flex-1 min-w-0 gap-3">
                        <a href="{{ route('profile.show', $suggestion->username) }}" wire:navigate class="flex-shrink-0">
                            <x-avatar :user="$suggestion" />
                        </a>
                        <div class="flex-1 min-w-0 leading-tight">
                            <a href="{{ route('profile.show', $suggestion->username) }}" wire:navigate class="font-bold text-slate-800 dark:text-slate-200 hover:underline">
                                {{ $suggestion->name }}
                            </a>
                            <p class="text-sm truncate text-slate-500 dark:text-slate-400">
                                @<span>{{ $suggestion->username }}</span>
                            </p>
                        </div>
                    </div>

                    {{-- Follow Button (X.com Style) --}}
                    <div class="flex-shrink-0 ml-2">
                         <button
                            wire:click="toggleFollow({{ $suggestion->id }})"
                            wire:loading.attr="disabled"
                            wire:target="toggleFollow({{ $suggestion->id }})"
                            class="px-4 py-1.5 text-sm font-bold bg-slate-900 text-white dark:bg-slate-50 dark:text-black rounded-full hover:opacity-90 focus:outline-none transition-opacity">
                            <span wire:loading.remove wire:target="toggleFollow({{ $suggestion->id }})">Follow</span>
                            <span wire:loading wire:target="toggleFollow({{ $suggestion->id }})">Following...</span>
                        </button>
                    </div>
                </div>
            @empty
                <p class="pt-2 text-sm text-center text-slate-500 dark:text-slate-400">Nothing to see here yet.</p>
            @endforelse

            {{-- "Show more" link --}}
            @if ($hasMoreSuggestions && $suggestions->isNotEmpty())
                <button wire:click="loadMore" wire:loading.attr="disabled"
                        class="block w-full pt-2 text-sm text-left text-blue-500 dark:text-blue-400 hover:underline focus:outline-none">
                    Show more
                </button>
            @endif
        @else
            {{-- Message for logged-out users --}}
            <p class="pt-2 text-sm text-slate-500 dark:text-slate-400">Login to get user suggestions.</p>
        @endif
    </div>
</div>
