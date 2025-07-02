<div>

    <div class="container min-h-screen p-0 mx-auto md:p-0" wire:poll.5s="checkForNewPosts">

        {{-- New posts indicator --}}
        @if ($newPostsCount > 0)
            <div x-data="{ show: false }" x-init="() => { setTimeout(() => show = true, 50); }" x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="fixed z-50 -translate-x-1/2 top-20 left-1/2" wire:key="new-posts-indicator">

                <button wire:click="prependNewPosts"
                    class="px-4 py-2 font-bold text-white transition-all duration-300 ease-in-out bg-blue-500 rounded-full shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                    Show {{ $newPostsCount }} new {{ str('post')->plural($newPostsCount) }}
                </button>
            </div>
        @endif

        <div x-data="{
            init() {
    const trigger = this.$refs.bottomTrigger;
    if (! trigger || @this.get('loadingMore')) {
        return;
    }

    new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                @this.call('scrollBottom');
            }
        });
    }, { root: null, threshold: 0.1 })
    .observe(trigger);
}

            }" class="max-w-2xl mx-auto bg-white border-r border-gray-200 dark:bg-black dark:border-slate-700">
            {{-- Post Loop --}}

            @forelse ($posts as $post)
                <article wire:key="post-{{ $post->id }}"
                    class="flex p-4 space-x-4 border-b border-gray-200 dark:border-slate-700">
                    {{-- Avatar Column --}}
                    <div class="flex-shrink-0">
                        <a href="{{ route('profile.show', ['username' => $post->user->username, 'user' => $post->user]) }}"
                            wire:navigate>
                            <x-avatar :user="$post->user" />
                        </a>
                    </div>

                    {{-- Content Column --}}
                    <div class="flex-1">
                        {{-- Post Header --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-start space-x-1 text-sm">
                                <a href="{{ route('profile.show', ['username' => $post->user->username, 'user' => $post->user]) }}"
                                    class="font-bold text-gray-900 truncate dark:text-white hover:underline" wire:navigate>
                                    <div class="flex flex-col">
                                        <span> {{ $post->user->name }}</span>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            @<span>
                                                {{ $post->user->username }}
                                            </span>
                                        </span>
                                    </div>

                                </a>

                                <span class="text-gray-500 dark:text-gray-400">&middot;</span>
                                <a href="{{ route('posts.show', $post->id) }}" wire:navigate
                                    class="text-gray-500 dark:text-gray-400 hover:underline">
                                    {{ $post->created_at->diffForHumans(null, true) }}
                                </a>
                            </div>


                            <!-- Dropdown menu -->
                            <div x-data="{ menuOpen: false, showDeleteModal: false }" class="relative">
                                <button @click="menuOpen = !menuOpen" class="text-gray-500 hover:text-slate-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>

                                <div x-show="menuOpen" @click.away="menuOpen = false" x-transition
                                    class="absolute right-0 z-20 w-48 py-1 mt-2 bg-white border border-gray-200 rounded-md shadow-xl dark:bg-slate-800 dark:border-slate-700"
                                    style="display: none;">
                                    <a href="{{ route('posts.show', $post->id) }}" wire:navigate
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Show Details
                                    </a>



                                    @if(auth()->id() === $post->user_id)
                                        <a href="{{ route('posts.edit', $post) }}" wire:navigate
                                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">
                                            <svg class="inline w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.232 5.232l3.536 3.536M9 13l6.536-6.536a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L5 11.828a2 2 0 010-2.828L9 13z" />
                                            </svg>
                                            Edit Post
                                        </a>
                                        <button @click.prevent="showDeleteModal = true; menuOpen = false"
                                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 dark:text-red-400">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Delete Post
                                        </button>
                                    @endif
                                </div>
                                <!-- Modal -->
                                <div x-show="showDeleteModal" x-transition x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                    <div
                                        class="w-full max-w-md p-6 mx-auto bg-white shadow-lg dark:bg-slate-800 rounded-xl">
                                        <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-100">
                                            Are you sure you want to delete this post?
                                        </h2>
                                        <p class="mb-6 text-sm text-gray-600 dark:text-gray-300">
                                            This action cannot be undone.
                                        </p>

                                        <div class="flex justify-end space-x-2">
                                            <button @click="showDeleteModal = false"
                                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md dark:text-gray-300 dark:bg-slate-700 hover:bg-gray-200">
                                                Cancel
                                            </button>

                                            <button
                                                @click="showDeleteModal = false; Livewire.dispatch('deletePost', { postId: {{ $post->id }} });"
                                                class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700">
                                                Delete
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Post Content --}}
                        <div class="mt-1 text-base text-gray-800 dark:text-gray-200">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                        <div class="p-3 mt-2">
                            @include('partials.posts._post-attachments')
                        </div>
                        <div class="post-action-wrapper">
                            {{-- X.com-style Action Bar --}}
                            <div class="flex items-center justify-between max-w-sm mt-4 text-gray-500">
                                {{-- Comment/Reply Button --}}
                                {{-- (!-- FIX: The click event now toggles the specific post's comment section --) --}}
                                <button wire:click="toggleComments({{ $post->id }})"
                                    class="flex items-center space-x-2 transition group">
                                    <div class="p-2 rounded-full group-hover:bg-blue-100 dark:group-hover:bg-blue-900/20">
                                        <svg class="w-5 h-5 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm group-hover:text-blue-500">{{ $post->comments->count() }}</span>
                                </button>

                                {{-- Repost Button (Placeholder) --}}
                                <button class="flex items-center space-x-2 transition group">
                                    <div class="p-2 rounded-full group-hover:bg-green-100 dark:group-hover:bg-green-900/20">
                                        <svg class="w-5 h-5 group-hover:text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h5"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 20v-5h-5"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 9a8 8 0 0114.24-4.76L20 5"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 15a8 8 0 01-14.24 4.76L4 19"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm group-hover:text-green-500">0</span>
                                </button>

                                {{-- Like Button --}}
                                {{-- (!-- FIX: Pass the post ID to the toggleLike method --) --}}
                                <button wire:click="toggleLike({{ $post->id }})"
                                    class="flex items-center space-x-2 transition group">
                                    <div class="p-2 rounded-full group-hover:bg-red-100 dark:group-hover:bg-red-900/20">
                                        <svg class="w-5 h-5 {{ $post->isLikedBy(auth()->user()) ? 'text-red-500' : 'group-hover:text-red-500' }}"
                                            fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-sm {{ $post->isLikedBy(auth()->user()) ? 'text-red-500' : 'group-hover:text-red-500' }}">{{ $post->likes->count() }}</span>
                                </button>

                                {{-- Share Button --}}
                                <button
                                    class="flex items-center p-2 transition rounded-full group hover:bg-blue-100 dark:hover:bg-blue-900/20">
                                    <svg class="w-5 h-5 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8m-4-6l-4-4m0 0L8 6m4-4v12" />
                                    </svg>
                                </button>
                            </div>

                            {{-- Comments Section with Animation --}}
                            {{-- (!-- FIX: Use the specific post's open/closed state from the array --) --}}
                            <div x-data="{ openComments: @entangle('openCommentsSection.' . $post->id) }"
                                x-show="openComments" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 -translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-4"
                                class="pt-4 mt-4 border-t border-gray-200 dark:border-slate-700" style="display: none;">
                                @auth
                                    <div class="flex w-full space-x-4">
                                        <img src="{{ Auth::user()->avatarUrl() }}" alt="{{ Auth::user()->name }}"
                                            class="object-cover w-10 h-10 rounded-full">
                                        <div class="flex-1">
                                            {{-- (!-- FIX: Wire model to the specific post's comment text in the array --) --}}
                                            <textarea wire:model.defer="newCommentText.{{ $post->id }}"
                                                placeholder="Post your reply" rows="2"
                                                class="w-full p-2 text-base bg-transparent border-b border-gray-300 rounded-lg dark:border-slate-600 focus:outline-none focus:border-blue-500 dark:text-white"></textarea>
                                            <div class="flex items-center justify-end mt-2">
                                                {{-- (!-- FIX: Point the error message to the correct array key --) --}}
                                                @error('newCommentText.' . $post->id) <span
                                                class="mr-auto text-xs text-red-500">{{ $message }}</span> @enderror
                                                {{-- (!-- FIX: Pass the post ID to the addComment method --) --}}
                                                <button wire:click="addComment({{ $post->id }})" wire:loading.attr="disabled"
                                                    wire:target="addComment"
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
                                            <x-avatar class="object-cover" :user="$comment->user" />
                                            <div class="flex-1">
                                                <div class="relative p-3 bg-gray-100 rounded-lg dark:bg-slate-800">
                                                    <div class="flex items-center space-x-2 text-sm">
                                                        <h4 class="font-bold text-gray-800 dark:text-white">
                                                            {{ $comment->user->name }}
                                                        </h4>
                                                        <span
                                                            class="text-gray-500 dark:text-gray-400">@<span>{{ $comment->user->username }}</span></span>
                                                        @if(Auth::id() === $comment->user_id)
                                                            {{-- (!-- FIX: Call the new confirmation method --) --}}
                                                            <button wire:click="requestDeleteConfirmation({{ $comment->id }})"
                                                                class="ml-auto text-gray-400 hover:text-red-500 focus:outline-none"
                                                                title="Delete Comment">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
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
                        </div>

                        {{-- This modal can live outside the post loop, at the bottom of your main component view --}}
                        <div x-data="{ show: @entangle('confirmingCommentDeletion') }" x-show="show" x-trap.noscroll="show"
                            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">

                            <div @click="show = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
                            <div @click.away="show = false"
                                class="relative w-full max-w-md p-6 mx-auto bg-white shadow-lg rounded-xl dark:bg-slate-800">
                                <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-100">Delete Comment?</h2>
                                <p class="mb-6 text-sm text-gray-600 dark:text-gray-300">
                                    Are you sure? This action is permanent.
                                </p>
                                <div class="flex justify-end space-x-3">
                                    <button @click="show = false"
                                        class="px-5 py-2 text-gray-700 transition bg-gray-100 rounded-md dark:text-gray-300 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600">
                                        Cancel
                                    </button>
                                    {{-- (!-- FIX: Call the new parameter-less deleteComment method --) --}}
                                    <button wire:click="deleteComment" wire:loading.attr="disabled"
                                        class="px-5 py-2 text-white transition bg-red-600 rounded-md hover:bg-red-700">
                                        <span wire:loading.remove wire:target="deleteComment">Delete</span>
                                        <span wire:loading wire:target="deleteComment">Deleting...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

            @empty
                {{-- only show this if posts collection is loaded and empty --}}
                @if ($posts !== null)
                    <div class="p-10 text-center">
                        <p class="text-gray-500 dark:text-gray-400">No posts to show.</p>
                    </div>
                @else
                    {{-- initial-loading skeleton --}}
                    <div wire:init="loadPosts" class="p-10 text-center">
                        <p class="text-gray-500 dark:text-gray-400">Loading posts...</p>
                    </div>
                @endif
            @endforelse

            {{-- after the @endforelse --}}
            @if ($posts->isNotEmpty())
                {{-- skeleton loaders --}}
                <div class="p-2 space-y-4 animate-pulse">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="flex p-2 space-x-4 border-b border-gray-200 dark:border-slate-700">
                            <!-- Avatar -->
                            <div class="w-12 h-12 rounded-full bg-slate-300 dark:bg-slate-600"></div>

                            <!-- Content -->
                            <div class="flex-1 py-1 space-y-3">
                                <!-- Title line -->
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-2 text-sm">
                                        <div class="flex flex-col space-y-1">
                                            <div class="w-24 h-4 bg-gray-300 rounded dark:bg-slate-600"></div>
                                            <div class="w-20 h-3 bg-gray-200 rounded dark:bg-slate-700"></div>
                                        </div>
                                        <div class="w-4 h-3 bg-gray-300 rounded dark:bg-slate-600"></div>
                                        <div class="w-16 h-3 bg-gray-300 rounded dark:bg-slate-600"></div>
                                    </div>
                                    <div class="flex flex-col items-center justify-center space-y-1">
                                        <div class="w-1 h-1 bg-gray-300 rounded-full dark:bg-slate-600"></div>
                                        <div class="w-1 h-1 bg-gray-300 rounded-full dark:bg-slate-600"></div>
                                        <div class="w-1 h-1 bg-gray-300 rounded-full dark:bg-slate-600"></div>
                                    </div>

                                </div> <!-- Text lines -->
                                {{-- Post Content --}}
                                <div class="mt-2 space-y-2">
                                    <div class="w-11/12 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>
                                    <div class="w-9/12 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>
                                    <div class="w-6/12 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>
                                </div>

                                {{-- Action Bar --}}

                                <div class="flex pt-2 space-x-4">
                                    <div class="w-16 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>
                                    <div class="w-16 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>
                                    <div class="w-16 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>
                                    <div class="w-16 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>

                                </div>
                            </div>
                        </div>


                    @endfor
                </div>

                {{-- trigger only if not already loading more --}}
                @if (!$loadingMore)
                    <div x-ref="bottomTrigger" class="h-0"></div>
                @endif
            @endif

        </div>
    </div>
    @include('partials.posts._image-modal')
</div>