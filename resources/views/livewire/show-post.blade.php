<div class="">
    <div class="container min-h-screen p-4 ">
        <div class="">

            <!-- Back to Feed Link -->
            <div class="mb-4">
                <a href="{{route('feed') }}" wire:navigate
                    class="inline-flex items-center text-sm font-semibold text-gray-600 transition-colors dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Feed
                </a>
            </div>

            <!-- Main Post Card -->
            <article class="flex flex-col overflow-hidden ">
                <div class="p-2 ">
                    <!-- Post Header -->
                    <div class="flex items-start justify-between">
                        <div class="flex items-center">
                            <a href="{{-- route('profile.show', $post->user) --}}" wire:navigate class="flex-shrink-0">
                                <img src="{{ $post->user->avatarUrl() }}" alt="{{ $post->user->name }}"
                                    class="object-cover w-12 h-12 rounded-full">
                            </a>
                            <div class="ml-4">
                                <a href="{{-- route('profile.show', $post->user) --}}" wire:navigate
                                    class="text-lg font-bold text-gray-900 dark:text-white hover:underline">
                                    {{ $post->user->name }}
                                </a>
                                <p class="text-sm text-gray-500 dark:text-gray-400"
                                    title="{{ $post->created_at->format('M d, Y \a\t h:i A') }}">
                                    Posted {{ $post->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @auth
                                @if (Auth::id() !== $post->user->id)
                                    <button wire:click="toggleFollow({{ $post->user->id }})" wire:loading.attr="disabled"
                                        class="hidden sm:inline-flex items-center text-xs font-semibold py-1.5 px-3 rounded-full transition-colors
                                                        {{ Auth::user()->isFollowing($post->user) ? 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' : 'bg-blue-500 hover:bg-blue-600 text-white' }}">
                                        <span>{{ Auth::user()->isFollowing($post->user) ? 'Following' : 'Follow' }}</span>
                                        <span wire:loading wire:target="toggleFollow"
                                            class="inline-block w-3 h-3 ml-2 border-2 border-current rounded-full border-t-transparent animate-spin"></span>
                                    </button>
                                @endif
                            @endauth
                            @include('partials.posts._post-options-menu')
                        </div>
                    </div>

                    <!-- Post Body -->
                    <div class="py-2 mt-4 text-gray-700 ">
                        <div class="prose prose-lg dark:prose-invert max-w-none">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div class="p-2">
                        @include('partials.posts._post-attachments')

                    </div>

                </div>

                <!-- Stats & Actions -->
                <div class="px-2 py-3 ">
                    <div class="flex items-center justify-between">
                        <!-- Stats -->
                        <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-1.5 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>{{ $likesCount }} {{ Str::plural('Like', $likesCount) }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-1.5 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>{{ $post->comments_count }}
                                    {{ Str::plural('Comment', $post->comments_count) }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-2">
                            <button wire:click="toggleLike" wire:loading.attr="disabled"
                                class="flex items-center px-3 py-1.5 space-x-2 text-sm font-semibold transition-colors rounded-full
                                {{ $isLikedByAuthUser ? 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>{{ $isLikedByAuthUser ? 'Liked' : 'Like' }}</span>
                            </button>
                            <button wire:click="toggleSave"
                                class="p-2 text-gray-500 transition-colors rounded-full hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Comments Section Card -->
            <section class="mt-10" aria-labelledby="comments-heading">
                <div class="p-2 ">
                    <h2 id="comments-heading" class="mb-6 text-xl font-semibold text-gray-900 dark:text-white">
                        💬 Comments ({{ $post->comments_count }})
                    </h2>

                    <!-- Add Comment Form -->
                    @auth
                        <form wire:submit.prevent="addComment" class="flex items-start mb-8 space-x-4">
                            <img src="{{ Auth::user()->avatar_url }}" alt="Your avatar"
                                class="object-cover w-10 h-10 border rounded-full shadow-sm">
                            <div class="flex-1">
                                <textarea wire:model="newCommentText" rows="3" placeholder="Join the discussion..."
                                    class="w-full text-sm border border-gray-300 dark:border-gray-600 rounded-md shadow-sm dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none p-2.5"></textarea>
                                @error('newCommentText')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <div class="flex justify-end mt-2">
                                    <button type="submit" wire:loading.attr="disabled"
                                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50">
                                        <span wire:loading.remove wire:target="addComment">Post Comment</span>
                                        <span wire:loading wire:target="addComment">Posting...</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="mb-6 text-center text-gray-500 dark:text-gray-400">
                            Please
                            <a href="{{ route('login') }}" wire:navigate
                                class="font-semibold text-blue-500 hover:underline">
                                sign in
                            </a>
                            to join the discussion.
                        </div>
                    @endauth

                    <!-- Comments List -->
                    <div class="space-y-6">
                        @forelse ($post->comments as $comment)
                            <div wire:key="comment-{{ $comment->id }}" class="flex items-start gap-4">
                                <a href="#" wire:navigate>
                                    <img src="{{ $comment->user->avatarUrl() }}" alt="{{ $comment->user->name }}"
                                        class="object-cover w-10 h-10 border rounded-full shadow dark:border-gray-600">
                                </a>
                                <div class="relative flex-1 px-4 py-3 bg-gray-100 rounded-lg group dark:bg-gray-800">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                            <a href="#" wire:navigate class="hover:underline">{{ $comment->user->name }}</a>
                                        </h4>
                                        <span class="text-xs text-gray-500 dark:text-gray-400"
                                            title="{{ $comment->created_at->format('M d, Y h:i A') }}">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-800 whitespace-pre-line dark:text-gray-300">
                                        {!! nl2br(e($comment->content)) !!}
                                    </p>

                                    @if (auth()->id() === $comment->user_id)
                                        <button wire:click="deleteComment({{ $comment->id }})" wire:loading.attr="disabled"
                                            class="absolute text-red-400 transition top-1 right-1 hover:text-red-600"
                                            title="Delete comment">
                                            <!-- Trash Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 7h12M9 7V4h6v3m2 0v12a2 2 0 01-2 2H8a2 2 0 01-2-2V7h12z" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center border-t border-gray-200 dark:border-gray-700">
                                <svg class="w-12 h-12 mx-auto text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 5.523-4.477 10-10 10S1 17.523 1 12 5.477 2 12 2s10 4.477 10 10z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No comments yet</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Be the first to share your
                                    thoughts!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

        </div>
    </div>

    <!-- Image Modal (Unchanged) -->
    @if($imageModalOpen && !empty($imageModalUrls))
        @include('partials.posts._image-modal')
    @endif
</div>
