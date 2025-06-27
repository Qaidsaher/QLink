<div wire:poll.5s='refreshPostData' class="post-action-wrapper" x-data="{ openComments: @entangle('openComments') }">

    {{-- X.com-style Action Bar --}}
    <div class="flex items-center justify-between max-w-sm mt-4 text-gray-500">
        {{-- Comment/Reply Button --}}
        <button @click="openComments = !openComments" class="flex items-center space-x-2 transition group">
            <div class="p-2 rounded-full group-hover:bg-blue-100 dark:group-hover:bg-blue-900/20">
                <svg class="w-5 h-5 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z" />
                </svg>
            </div>
            <span class="text-sm group-hover:text-blue-500">{{ $post->comments_count }}</span>
        </button>

        {{-- Repost Button (Placeholder) --}}
        <button class="flex items-center space-x-2 transition group">
            <div class="p-2 rounded-full group-hover:bg-green-100 dark:group-hover:bg-green-900/20">
                <svg class="w-5 h-5 group-hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 20v-5h-5"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 9a8 8 0 0114.24-4.76L20 5"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 15a8 8 0 01-14.24 4.76L4 19"></path>
                </svg>
            </div>
            <span class="text-sm group-hover:text-green-500">0</span>
        </button>

        {{-- Like Button --}}
        <button wire:click="toggleLike" class="flex items-center space-x-2 transition group">
            <div class="p-2 rounded-full group-hover:bg-red-100 dark:group-hover:bg-red-900/20">
                <svg class="w-5 h-5 {{ $post->isLikedBy(auth()->user()) ? 'text-red-500' : 'group-hover:text-red-500' }}"
                    fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <span
                class="text-sm {{ $post->isLikedBy(auth()->user()) ? 'text-red-500' : 'group-hover:text-red-500' }}">{{ $post->likes_count }}</span>
        </button>

        {{-- Share Button --}}
        <button class="flex items-center p-2 transition rounded-full group hover:bg-blue-100 dark:hover:bg-blue-900/20">
            <svg class="w-5 h-5 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8m-4-6l-4-4m0 0L8 6m4-4v12" />
            </svg>
        </button>
    </div>

    {{-- Comments Section with Animation --}}
    <div x-show="openComments" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="pt-4 mt-4 border-t border-gray-200 dark:border-slate-700" style="display: none;">
        @auth
            <div class="flex w-full space-x-4">
                <img src="{{ Auth::user()->avatarUrl() }}" alt="{{ Auth::user()->name }}"
                    class="object-cover w-10 h-10 rounded-full">
                <div class="flex-1">
                    <textarea wire:model.defer="newCommentText" placeholder="Post your reply" rows="2"
                        class="w-full p-2 text-base bg-transparent border-b border-gray-300 rounded-lg dark:border-slate-600 focus:outline-none focus:border-blue-500 dark:text-white"></textarea>
                    <div class="flex items-center justify-end mt-2">
                        @error('newCommentText') <span class="mr-auto text-xs text-red-500">{{ $message }}</span> @enderror
                        <button wire:click="addComment" wire:loading.attr="disabled" wire:target="addComment"
                            class="px-4 py-1.5 text-sm font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600 disabled:opacity-50 min-w-[70px] text-center">
                            <span wire:loading.remove wire:target="addComment">Reply</span>
                            <span wire:loading wire:target="addComment">Replying...</span>
                        </button>
                    </div>
                </div>
            </div>
        @endauth

        <div class="mt-6 space-y-6" wire:key="comments-list-{{ $post->id }}">
            @forelse($post->comments as $comment)
                <div wire:key="comment-{{ $comment->id }}" class="flex space-x-4">
                    <x-avatar class="object-conver " :user="$comment->user" />
                    <div class="flex-1">
                        <div class="relative p-3 bg-gray-100 rounded-lg dark:bg-slate-800">
                            <div class="flex items-center space-x-2 text-sm">
                                <h4 class="font-bold text-gray-800 dark:text-white">{{ $comment->user->name }}</h4>
                                <span
                                    class="text-gray-500 dark:text-gray-400">@<span>{{ $comment->user->username }}</span></span>
                                @if(Auth::id() === $comment->user_id)
                                    <button wire:click="requestDeleteConfirmation({{ $comment->id }})"
                                        class="ml-auto text-gray-400 hover:text-red-500 focus:outline-none"
                                        title="Delete Comment">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            <p class="mt-1 text-gray-700 whitespace-pre-line dark:text-gray-300">
                                {!! nl2br(e($comment->content)) !!}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="py-3 text-sm text-center text-gray-500 dark:text-gray-400">No replies yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: @entangle('confirmingCommentDeletion') }" x-show="show" x-trap.noscroll="show"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">

        <div @click="show = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <!-- Backdrop and modal wrapper -->
        <div x-show="show" x-transition x-cloak @click.away="show = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            style="will-change: opacity;">
            <!-- Modal box -->
            <div class="relative w-full max-w-md p-6 mx-auto bg-white shadow-lg rounded-xl dark:bg-slate-800"
                x-transition x-on:keydown.escape.window="show = false">
                <!-- Close (X) icon top-right -->
                <button @click="show = false" aria-label="Close modal"
                    class="absolute p-1 text-gray-500 transition rounded top-2 right-2 hover:text-red-500 dark:hover:text-red-500 dark:text-gray-400"
                    title="Close">
                    <!-- Heroicons X icon SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Modal content -->
                <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-100">
                    Are you sure you want to delete this comment?
                </h2>

                <p class="mb-6 text-sm text-gray-600 dark:text-gray-300">
                    This action cannot be undone and will permanently remove the comment from this post.
                </p>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <button @click="show = false" wire:loading.attr="disabled" wire:target="deleteComment"
                        class="px-5 py-2 text-gray-700 transition bg-gray-100 rounded-md dark:text-gray-300 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600">
                        Cancel
                    </button>

                    <button wire:click="deleteComment" wire:loading.attr="disabled" wire:target="deleteComment"
                        class="px-5 py-2 text-white transition bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                        <span wire:loading.remove wire:target="deleteComment">Delete</span>
                        <span wire:loading wire:target="deleteComment">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>