<div>
    @if(Auth::check() && Auth::id() !== $userIdToFollow)
        <button wire:click="toggleFollow" wire:loading.attr="disabled" wire:target="toggleFollow"
                class="font-semibold rounded-full transition-colors flex items-center justify-center shadow-sm
                       {{ $isSmall ? 'px-3 py-1 text-xs' : 'px-4 py-2 text-sm' }}
                       {{ $isFollowing ?
                            'bg-sky-100 text-sky-700 dark:bg-sky-700 dark:text-sky-200 hover:bg-sky-200 dark:hover:bg-sky-600 border border-sky-300 dark:border-sky-600' :
                            'bg-slate-800 text-white hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600' }}">

            <span wire:loading wire:target="toggleFollow"
                  class="animate-spin h-4 w-4 border-2 border-current border-r-transparent rounded-full
                         {{ $isSmall ? '-ml-0.5 mr-1.5' : '-ml-1 mr-2' }}">
            </span>

            <span wire:loading.remove wire:target="toggleFollow">
                @if($isFollowing)
                    Following
                @else
                    Follow
                @endif
            </span>
            <span wire:loading wire:target="toggleFollow">Wait...</span>
        </button>
    @elseif(!Auth::check())
        {{-- Optionally show a login prompt or a disabled follow button --}}
        <a href="{{ route('login') }}"
            class="font-semibold rounded-full transition-colors flex items-center justify-center shadow-sm
                   {{ $isSmall ? 'px-3 py-1 text-xs' : 'px-4 py-2 text-sm' }}
                   bg-slate-800 text-white hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600">
            Follow
        </a>
    @endif
    {{-- If Auth::id() === $userIdToFollow, nothing is rendered (can't follow self) --}}
</div>
