<div>
    {{-- Toast Message Display Area (requires Alpine.js or custom JS) --}}
    <div x-data="{ show: false, message: '', type: 'success' }"
         @toast-message.window="message = $event.detail.message; type = $event.detail.type || 'success'; show = true; setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed z-50 p-4 text-white rounded-md shadow-lg top-5 right-5"
         :class="{ 'bg-green-500': type === 'success', 'bg-red-500': type === 'error', 'bg-blue-500': type === 'info' }"
         style="display: none;">
        <p x-text="message"></p>
    </div>

    {{-- Slim Create Post Form (can be a separate component) --}}
    @auth
        <div class="p-4 bg-white border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900">
            <div class="flex space-x-3">
                <a href="{{ route('profile.show', Auth::user()->username) }}">
                    <img class="object-cover w-10 h-10 rounded-full" src="{{ Auth::user()->avatarUrl() }}" alt="My Avatar">
                </a>
                <div class="flex-1">
                    {{-- This could be a simplified Livewire component for quick posting --}}
                    <a href="{{ route('posts.create') }}" class="block w-full p-2.5 bg-gray-100 dark:bg-gray-800 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-sm">
                        What's happening, {{ Auth::user()->name }}?
                    </a>
                </div>
            </div>
            {{-- Icons for quick actions could be added here, linking to posts.create with params --}}
        </div>
    @endauth

    @if ($posts->isEmpty() && !$this->getPropertyValue('page')) {{-- Check if truly no posts, not just empty current page --}}
        <div class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
            <i class="mb-4 text-gray-300 fas fa-stream fa-3x dark:text-gray-600"></i>
            <h3 class="mb-2 text-xl font-semibold">Your Feed is Empty</h3>
            <p class="text-sm">Start following people or explore to see posts here.</p>
            {{-- <a href="#" class="inline-block px-4 py-2 mt-4 text-sm font-medium text-white rounded-full bg-primary-DEFAULT hover:bg-primary-dark">Explore Users</a> --}}
        </div>
    @else
        <div wire:loading.remove> {{-- Hide posts list while initial loading if preferred --}}
            @foreach ($posts as $post)
                @livewire('post-card', ['post' => $post], key($post->id . '-feed-' . $post->updated_at->timestamp . '-' . rand()))
            @endforeach
        </div>

        {{-- Loading indicator for initial load or load more --}}
        <div wire:loading wire:target="loadMore, nextPage, previousPage, gotoPage" class="w-full py-8 text-center">
            <i class="fas fa-circle-notch fa-spin fa-2x text-primary-DEFAULT"></i>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Loading posts...</p>
        </div>
         <div wire:loading.remove> {{-- Only show load more if not loading --}}
            @if ($posts->hasMorePages())
                <div class="mt-8 mb-4 text-center">
                    <button wire:click="loadMore"
                            class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-DEFAULT dark:focus:ring-offset-gray-900">
                        Load More Posts
                    </button>
                </div>
            @elseif(count($posts) > 0)
                <p class="py-8 text-sm text-center text-gray-400 dark:text-gray-500">You've reached the end!</p>
            @endif
        </div>
    @endif

    {{-- For debugging Pusher events --}}
    <script>
        document.addEventListener('livewire:load', function () {
            window.addEventListener('log-pusher', event => {
                console.log('Pusher Event:', event.detail.message, event.detail.data);
            });
        });
         // Script for toast messages from session flash
        @if(session()->has('toast-message'))
            window.dispatchEvent(new CustomEvent('toast-message', { detail: { message: '{{ session('toast-message') }}', type: '{{ session('toast-type', 'success') }}' }}));
        @endif
         @if(session()->has('toast-error'))
            window.dispatchEvent(new CustomEvent('toast-message', { detail: { message: '{{ session('toast-error') }}', type: 'error' }}));
        @endif
    </script>
</div>
