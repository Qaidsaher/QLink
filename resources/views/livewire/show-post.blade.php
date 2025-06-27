<div
    class="max-w-2xl min-h-screen mx-auto bg-white border-l border-r border-gray-200 dark:bg-slate-900/50 dark:border-slate-700">

    {{-- X.com Style Header: Sticky with Back button --}}
    <header
        class="sticky top-0 z-20 flex items-center p-2 border-b border-gray-200 bg-white/80 backdrop-blur-sm dark:bg-slate-900/80 dark:border-slate-700">
        <a href="{{ route('feed') }}" wire:navigate
            class="p-2 transition-colors rounded-full hover:bg-gray-200 dark:hover:bg-slate-800">
            <svg class="w-5 h-5 text-gray-800 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="ml-4 text-xl font-bold text-gray-900 dark:text-white">Post</h1>
    </header>

    {{-- Main Post Content --}}
    <article class="p-4">
        {{-- Post Author Info --}}
        <div class="flex items-center space-x-4">
            <a href="{{-- route('profile.show', $post->user) --}}" wire:navigate class="flex-shrink-0">
                <img src="{{ $post->user->avatarUrl() }}" alt="{{ $post->user->name }}"
                    class="object-cover w-12 h-12 rounded-full">
            </a>
            <div class="flex-1">
                <a href="{{-- route('profile.show', $post->user) --}}" wire:navigate
                    class="font-bold text-gray-900 dark:text-white hover:underline">
                    {{ $post->user->name }}
                </a>
                <p class="text-sm text-gray-500 dark:text-gray-400">@<span>{{ $post->user->username }}</span></p>
            </div>
            @auth
                @if (Auth::id() !== $post->user->id)
                    <button wire:click="toggleFollow" wire:loading.attr="disabled"
                        class="px-4 py-1.5 text-sm font-bold {{ Auth::user()->isFollowing($post->user) ? 'bg-transparent border border-gray-300 dark:border-gray-600 text-black dark:text-white' : 'bg-black dark:bg-white text-white dark:text-black' }} rounded-full transition-colors">
                        <span>{{ Auth::user()->isFollowing($post->user) ? 'Following' : 'Follow' }}</span>
                    </button>
                @endif
            @endauth
        </div>

        {{-- Post Body --}}
        <div class="px-5 py-2 mt-2 text-xl text-gray-800 dark:text-gray-200">
            {!! nl2br(e($post->content)) !!}
        </div>

        {{-- Attachments --}}
        <div class="p-3 mt-2">
            @include('partials.posts._post-attachments')
        </div>

        {{-- Detailed Timestamp --}}
        <div class="py-4 text-sm text-gray-500 border-b border-gray-200 dark:border-slate-700 dark:text-gray-400">
            <span>{{ $post->created_at->format('g:i A · M j, Y') }}</span>
        </div>

        {{-- Stats (Likes/Comments) --}}
        @if($likesCount > 0 || $post->comments_count > 0)
            <div
                class="flex items-center gap-4 py-4 text-sm text-gray-500 border-b border-gray-200 dark:border-slate-700 dark:text-gray-400">
                @if($likesCount > 0)
                    <a href="#" class="hover:underline"><strong class="text-black dark:text-white">{{ $likesCount }}</strong>
                        {{ Str::plural('Like', $likesCount) }}</a>
                @endif
                @if($post->comments_count > 0)
                    <a href="#" class="hover:underline"><strong
                            class="text-black dark:text-white">{{ $post->comments_count }}</strong>
                        {{ Str::plural('Reply', $post->comments_count) }}</a>
                @endif
            </div>
        @endif

        {{-- Action Bar (Same as feed) --}}
        <footer
            class="flex items-center justify-around py-2 text-gray-500 border-b border-gray-200 dark:border-slate-700">
            <button wire:click="toggleComments" class="flex items-center space-x-2 transition group">
                <div class="p-2 rounded-full group-hover:bg-blue-100 dark:group-hover:bg-blue-800/30"><svg
                        class="w-6 h-6 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z" />
                    </svg></div>
            </button>
            <button wire:click="toggleLike" class="flex items-center space-x-2 transition group">
                <div class="p-2 rounded-full group-hover:bg-red-100 dark:group-hover:bg-red-800/30">
                    <svg class="w-6 h-6 @if($isLikedByAuthUser) text-red-500 @else group-hover:text-red-500 @endif"
                        fill="@if($isLikedByAuthUser) currentColor @else none @endif" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
            </button>
            <button wire:click="toggleSave" class="flex items-center space-x-2 transition group">
                <div class="p-2 rounded-full group-hover:bg-blue-100 dark:group-hover:bg-blue-800/30"><svg
                        class="w-6 h-6 text-blue-500 group-hover:text-blue-500 "
                        fill="currentColor  none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg></div>
            </button>
            <button class="flex items-center space-x-2 transition group">
                <div class="p-2 rounded-full group-hover:bg-blue-100 dark:group-hover:bg-blue-800/30"><svg
                        class="w-6 h-6 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8m-4-6l-4-4m0 0L8 6m4-4v12" />
                    </svg></div>
            </button>
        </footer>
    </article>

    {{-- Reply Form Section --}}
    @auth
        <div class="p-4 border-b border-gray-200 dark:border-slate-700">
            <form wire:submit.prevent="addComment" class="flex items-start space-x-4">
                <img src="{{ Auth::user()->avatarUrl() }}" alt="Your avatar" class="flex-shrink-0 w-12 h-12 rounded-full">
                <div class="flex-1">
                    <textarea wire:model="newCommentText" rows="1" placeholder="Post your reply"
                        class="w-full text-lg bg-transparent border-0 resize-none dark:text-gray-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:ring-0"></textarea>
                    @error('newCommentText') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <button type="submit" wire:loading.attr="disabled"
                    class="px-5 py-2 text-sm font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600 disabled:opacity-70">
                    Reply
                </button>
            </form>
        </div>
    @endauth

    {{-- Comments/Replies List --}}
    <section aria-labelledby="replies-heading">
        <h2 id="replies-heading" class="sr-only">Replies</h2>
        <div>
            @forelse ($post->comments as $comment)
                {{-- Each comment is rendered like a post from the main feed --}}
                <div wire:key="comment-{{ $comment->id }}"
                    class="flex p-4 space-x-4 border-b border-gray-200 dark:border-slate-700">
                    <div class="flex-shrink-0">
                        <a href="{{-- route('profile.show', $comment->user) --}}"><img
                                src="{{ $comment->user->avatarUrl() }}" alt="{{ $comment->user->name }}"
                                class="w-12 h-12 rounded-full"></a>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-1 text-sm">
                                <a href="{{-- route('profile.show', $comment->user) --}}"
                                    class="font-bold text-gray-900 dark:text-white hover:underline">{{ $comment->user->name }}</a>
                                <span
                                    class="text-gray-500 dark:text-gray-400">@<span>{{ $comment->user->username }}</span></span>
                                <span class="text-gray-500 dark:text-gray-400">&middot;</span>
                                <span
                                    class="text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            @if (auth()->id() === $comment->user_id)
                                <button wire:click="deleteComment({{ $comment->id }})" title="Delete comment"
                                    class="p-1 text-gray-400 rounded-full hover:bg-red-100 hover:text-red-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        <p class="mt-1 text-gray-800 dark:text-gray-200">
                            {!! nl2br(e($comment->content)) !!}</p>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-gray-500 dark:text-gray-400">
                    <p>No replies yet. Be the first!</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Image Modal (Your existing partial or logic goes here) -->
    @if($imageModalOpen && !empty($imageModalUrls))

        <div x-data x-show="$wire.imageModalOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @keydown.escape.window="$wire.closeImageModal()"
            @keydown.arrow-left.window="$wire.navigateImageModal(-1)"
            @keydown.arrow-right.window="$wire.navigateImageModal(1)"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90"
            x-trap.noscroll.inert="$wire.imageModalOpen" style="display: none;" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <!-- Main Modal Content -->
            <div @click.away="$wire.closeImageModal()" class="relative flex flex-col w-full h-full">

                <!-- Close Button (Top Right) -->
                <button wire:click="closeImageModal()"
                    class="absolute top-0 right-0 z-20 m-4 text-white rounded-full opacity-70 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-black/50">
                    <span class="sr-only">Close</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Image Container -->
                <div class="relative flex items-center justify-center flex-grow w-full h-full" aria-hidden="true">
                    @if(isset($imageModalUrls[$currentImageModalIndex]))
                        <img src="{{ $imageModalUrls[$currentImageModalIndex]['url'] }}" alt="Enlarged view"
                            class="object-contain w-auto h-auto max-w-full max-h-full rounded-lg shadow-2xl">
                    @endif
                </div>

                <!-- Controls and Pagination (Bottom Center) -->
                <div class="absolute bottom-0 left-0 right-0 z-20 flex items-center justify-center p-4">
                    <div class="flex items-center px-4 py-2 space-x-6 rounded-full bg-black/50 backdrop-blur-sm">
                        <!-- Previous Button -->
                        <button wire:click="navigateImageModal(-1)" @disabled($currentImageModalIndex === 0)
                            class="text-white rounded-full disabled:opacity-30 disabled:cursor-not-allowed hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white">
                            <span class="sr-only">Previous</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Pagination -->
                        <p class="text-lg font-medium text-white select-none" aria-live="polite">
                            {{ $currentImageModalIndex + 1 }} / {{ count($imageModalUrls) }}
                        </p>

                        <!-- Next Button -->
                        <button wire:click="navigateImageModal(1)" @disabled($currentImageModalIndex >= count($imageModalUrls) - 1)
                            class="text-white rounded-full disabled:opacity-30 disabled:cursor-not-allowed hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white">
                            <span class="sr-only">Next</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    @endif
</div>