<div x-data="{ menuOpen: false }" class="relative">
    <button @click="menuOpen = !menuOpen" class="p-2 text-gray-500 transition-colors rounded-full hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
    </button>
    <div x-show="menuOpen" @click.away="menuOpen = false"
        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 z-30 w-48 py-1 mt-2 origin-top-right bg-white border border-gray-200 rounded-md shadow-xl dark:bg-gray-800 dark:border-gray-700" style="display: none;">
        @can('update', $post)
            <a href="#" class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Edit Post</a>
        @endcan
        @can('delete', $post)
            <button wire:click="deletePost" wire:confirm="Are you sure you want to delete this post?" class="flex items-center w-full px-4 py-2 text-sm text-left text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10">Delete Post</button>
        @endcan
        @if(Auth::check() && Auth::id() !== $post->user_id)
            <div class="my-1 border-t border-gray-200 dark:border-gray-700"></div>
            <a href="#" class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Report Post</a>
        @endif
    </div>
</div>
