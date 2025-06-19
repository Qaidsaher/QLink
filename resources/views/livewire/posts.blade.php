<div>
    @livewire('post-create')

    <div class="container min-h-screen p-4 mx-auto md:p-6">
        <div class="max-w-2xl mx-auto space-y-6">
            {{-- @if(isset($newPostsCount) && $newPostsCount > 0) --}}
            {{-- <div class="mb-4">
                <button
                    class="w-full px-4 py-2 text-base font-medium text-white transition duration-200 bg-blue-600 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    New Post{{ 's' }} Available - Click to Load!
                </button>
            </div> --}}
            {{-- @endif --}}
            @if($posts && $posts->isNotEmpty())
                @foreach ($posts as $post)
                    <article wire:key="post-{{ $post->id }}"
                        class="flex flex-col overflow-hidden bg-white rounded-md shadow-md dark:bg-slate-900">
                        <!-- Post Content Block -->
                        <div class="flex-grow p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <a href="{{ route('profile.show', ['username' => $post->user->username, 'user' => $post->user]) }}"
                                        class="flex-shrink-0 group">
                                       <x-avatar :user="$post->user" class="mr-3" />
                                    </a>
                                    <div>
                                        <a href="{{ route('profile.show', ['username' => $post->user->username, 'user' => $post->user]) }}"
                                            class="font-semibold text-gray-900 transition-colors dark:text-white hover:underline hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ $post->user->name }}
                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $post->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @auth
                                        @if (Auth::id() !== $post->user->id)
                                            <button wire:click="toggleFollow({{ $post->user->id }})" wire:loading.attr="disabled"
                                                class="text-xs font-semibold py-1.5 px-3 rounded-full transition-colors duration-150 ease-in-out
                                                                                                                {{ Auth::user()->isFollowing($post->user) ? 'bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300' : 'bg-blue-500 hover:bg-blue-600 text-white' }}">
                                                {{ Auth::user()->isFollowing($post->user) ? 'Following' : 'Follow' }}
                                                <span wire:loading wire:target="toggleFollow({{ $post->user->id }})"
                                                    class="inline-block w-3 h-3 ml-1 border-t-2 border-r-2 border-current rounded-full animate-spin"></span>
                                            </button>
                                        @endif
                                    @endauth
                                    <div x-data="{ menuOpen: false }" class="relative">
                                        <button @click="menuOpen = !menuOpen"
                                            class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        <div x-show="menuOpen" @click.away="menuOpen = false"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            class="absolute right-0 z-20 w-48 py-1 mt-2 bg-white border border-gray-200 rounded-md shadow-xl dark:bg-gray-800 dark:border-gray-700"
                                            style="display: none;">
                                            <a href="#"
                                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Report
                                                Post</a>
                                            {{-- Add more menu items --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mb-3 text-sm leading-relaxed prose text-gray-700 dark:text-gray-300 dark:prose-invert max-w-none">
                                {!! nl2br(e($post->content)) !!} {{-- Use nl2br for newlines, e() for escaping --}}
                            </div>
                            <a href="{{ route('posts.show', $post->id) }}" wire:navigate
                                class="text-sm text-blue-500 hover:underline">
                                Show Details
                            </a>

                            @php
                                $imageAttachments = $post->attachments->where('file_type', 'image');
                                $otherAttachments = $post->attachments->whereNotIn('file_type', ['image']);
                            @endphp

                            @if($imageAttachments->isNotEmpty())
                                <div class="mt-4 -mx-1 overflow-hidden rounded-md">
                                    <div class="grid gap-1
                                                                                    @if($imageAttachments->count() === 1) grid-cols-1
                                                                                    @elseif($imageAttachments->count() >= 2) grid-cols-2
                                                                                    @endif">
                                        @foreach($imageAttachments->take(4) as $index => $attachment)
                                            <div
                                                class="relative bg-gray-200 dark:bg-gray-700
                                                                                                        @if($imageAttachments->count() === 1) aspect-w-16 aspect-h-9 @else aspect-square @endif">
                                                <img src="{{ $attachment->file_url }}" alt="Post image"
                                                    wire:click="openImageModal({{ $post->id }}, {{ $index }})"
                                                    class="object-cover w-full h-full transition-opacity duration-150 cursor-pointer hover:opacity-90">
                                                @if($imageAttachments->count() > 4 && $loop->iteration === 4)
                                                    <div wire:click="openImageModal({{ $post->id }}, 3)"
                                                        class="absolute inset-0 flex items-center justify-center text-2xl font-bold text-white transition-all bg-black cursor-pointer bg-opacity-60 hover:bg-opacity-70">
                                                        +{{ $imageAttachments->count() - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($otherAttachments->isNotEmpty())
                                <div class="mt-3 space-y-2">
                                    @foreach($otherAttachments as $attachment)
                                        <div
                                            class="p-3 bg-gray-100 border border-gray-200 rounded-md dark:bg-gray-700/60 dark:border-gray-600/50">
                                            @if($attachment->file_type === 'video')
                                                <div class="flex items-center text-gray-700 dark:text-gray-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="flex-shrink-0 w-10 h-10 mr-3 text-blue-500 dark:text-blue-400"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.707-10.293l4-4a1 1 0 011.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" transform="matrix(-1 0 0 1 19 0) rotate(180 9.5 9.5)" />
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-semibold">{{ $attachment->fileName() }}</p>
                                                        <a href="{{ $attachment->file_url }}" target="_blank"
                                                            class="text-xs text-blue-500 dark:text-blue-400 hover:underline">Watch video</a>
                                                    </div>
                                                </div>
                                            @elseif($attachment->file_type === 'pdf')
                                                <div class="flex items-center text-gray-700 dark:text-gray-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="flex-shrink-0 w-10 h-10 mr-3 text-red-500 dark:text-red-400"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-semibold">{{ $attachment->fileName() }}</p>
                                                        <a href="{{ $attachment->file_url }}" target="_blank" download
                                                            class="text-xs text-blue-500 dark:text-blue-400 hover:underline">Download
                                                            PDF</a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Action Bar -->
                        <div class="flex items-center justify-around p-2 border-t border-gray-200 dark:border-gray-700">
                            <button wire:click="toggleLike({{ $post->id }})" wire:loading.attr="disabled"
                                class="flex items-center space-x-1 p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors
                                                                        {{ $post->is_liked ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                                    fill="{{ $post->is_liked ? 'currentColor' : 'none' }}"
                                    stroke="{{ $post->is_liked ? 'none' : 'currentColor' }}" stroke-width="1.5">
                                    <path fill-rule="evenodd"
                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm">{{ $post->likes_count }}</span>
                                <span wire:loading wire:target="toggleLike({{ $post->id }})"
                                    class="inline-block w-3 h-3 ml-1 border-t-2 border-r-2 border-current rounded-full animate-spin"></span>
                            </button>
                            <button wire:click="toggleComments({{ $post->id }})"
                                class="flex items-center p-2 space-x-1 text-gray-500 transition-colors rounded-md dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zm-4 0H9v2h2V9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm">{{ $post->comments_count }}</span>
                            </button>
                            <button
                                class="flex items-center p-2 space-x-1 text-gray-500 transition-colors rounded-md dark:text-gray-400 hover:text-green-500 dark:hover:text-green-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                                </svg>
                                <span class="text-sm">Share</span>
                            </button>
                            <button wire:click="toggleSave({{ $post->id }})" class="text-gray-500 dark:text-gray-400 hover:text-yellow-500 dark:hover:text-yellow-400 flex items-center space-x-1 p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors
                                                                    {{-- Add saved state class here --}}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="none"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-3.13L5 18V4z" />
                                </svg>
                                {{-- <span class="text-sm" x-show="post.saved">Saved</span> --}}
                            </button>
                        </div>

                        <!-- Comments Section -->
                        @if($openCommentsSection[$post->id] ?? false)
                            <div wire:key="comments-section-{{ $post->id }}"
                                class="p-5 bg-white border-t border-gray-200 shadow-sm dark:bg-gray-900 dark:border-gray-700 rounded-b-xl">

                                {{-- Authenticated User --}}
                                @auth
                                    <div class="flex items-start gap-3 mb-4">
                                        
                                        <img src="{{ Auth::user()->avatarUrl() }}" alt="{{ Auth::user()->name }}"
                                            class="object-cover w-10 h-10 border border-gray-300 rounded-full shadow-sm dark:border-gray-600">
                                        <div class="flex-1">
                                            <textarea wire:model.defer="newCommentText.{{ $post->id }}"
                                                placeholder="Share your thoughts..." rows="2"
                                                class="w-full p-3 text-sm transition duration-150 border border-gray-300 rounded-lg shadow-sm resize-none bg-gray-50 dark:bg-gray-800 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white"></textarea>
                                            @error("newCommentText.{$post->id}")
                                                <span class="text-xs text-red-500">{{ $message }}</span>
                                            @enderror

                                            <div class="flex justify-end mt-2">
                                                <button wire:click="addComment({{ $post->id }})" wire:loading.attr="disabled"
                                                    class="px-4 py-2 text-xs font-semibold text-white transition-all bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                                    Post Comment
                                                    <span wire:loading wire:target="addComment({{ $post->id }})"
                                                        class="inline-block w-4 h-4 ml-2 border-2 border-white rounded-full border-t-transparent animate-spin"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                                        Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">login</a> to
                                        comment.
                                    </p>
                                @endauth

                                {{-- Comments List --}}
                                <div class="pr-1 space-y-4 overflow-y-auto max-h-64 custom-scroll">
                                    @forelse($post->comments as $comment)
                                        <div wire:key="comment-{{ $comment->id }}" class="flex items-start gap-3">
                                            <img src="{{ $comment->user->avatarUrl() }}" alt="{{ $comment->user->name }}"
                                                class="object-cover border border-gray-300 rounded-full shadow-sm w-9 h-9 dark:border-gray-600">
                                            <div class="relative flex-1 px-4 py-3 bg-gray-100 rounded-lg dark:bg-gray-800 group">
                                                <div class="flex items-center justify-between ">
                                                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white">
                                                        {{ $comment->user->name }}
                                                    </h4>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $comment->created_at->diffForHumans() }}
                                                    </span>
                                                </div>

                                                <p class="text-sm leading-relaxed text-gray-700 whitespace-pre-line dark:text-gray-300">
                                                    {!! nl2br(e($comment->content)) !!}
                                                </p>

                                                {{-- Delete Button for Owner --}}
                                                @if(Auth::id() === $comment->user_id)
                                                    <button wire:click="deleteComment({{ $comment->id }}, {{ $post->id }})"
                                                        wire:loading.attr="disabled"
                                                        class="absolute text-red-400 transition top-1 right-1 hover:text-red-600"
                                                        title="Delete comment">
                                                        <!-- Heroicon Trash Icon -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <p class="py-3 text-sm text-center text-gray-500 dark:text-gray-400">
                                            No comments yet. Be the first!
                                        </p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                    </article>
                @endforeach

                @if($hasMorePages)
                    <div class="mt-10 text-center">
                        <button wire:click="loadMore" wire:loading.attr="disabled"
                            class="relative inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg transition-all duration-200 ease-in-out disabled:opacity-70 disabled:cursor-not-allowed">

                            <svg wire:loading wire:target="loadMore" class="absolute w-5 h-5 text-white left-4 animate-spin"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2
                                5.291A7.962 7.962 0 014 12H0c0 3.042
                                1.135 5.824 3 7.938l3-2.647z" />
                            </svg>

                            <span wire:loading.remove wire:target="loadMore">Load More Posts</span>
                            <span wire:loading wire:target="loadMore" class="ml-2">Loading...</span>
                        </button>
                    </div>

                @else
                    @if($posts->isNotEmpty())
                        <div class="mt-8 text-center text-gray-500 dark:text-gray-400">
                            You've reached the end! 🎉
                        </div>
                    @endif
                @endif
            @else
                <div wire:init="loadPosts" class="py-10 text-center">
                    <p class="text-gray-500 dark:text-gray-400">Loading posts...</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Image Modal (Livewire Controlled) -->
    @if($imageModalOpen && !empty($imageModalUrls))
        <div x-data @keydown.escape.window="$wire.closeImageModal()"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-80" x-trap.noscroll="true"
            x-show="$wire.imageModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="$wire.closeImageModal()"
                class="relative flex flex-col w-full p-2 bg-white rounded-lg shadow-xl dark:bg-gray-900 max-w-4xl max-h-[90vh]">
                @if(isset($imageModalUrls[$currentImageModalIndex]))
                    <img src="{{ $imageModalUrls[$currentImageModalIndex]['url'] }}" alt="Enlarged image"
                        class="object-contain w-full h-auto rounded flex-grow max-h-[calc(90vh-80px)]">
                @endif
                <div class="flex items-center justify-between pt-3 mt-auto">
                    <button wire:click="navigateImageModal(-1)" @disabled($currentImageModalIndex === 0)
                        class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span>{{ $currentImageModalIndex + 1 }}</span> / <span>{{ count($imageModalUrls) }}</span>
                    </p>
                    <button wire:click="navigateImageModal(1)"
                        @disabled($currentImageModalIndex===count($imageModalUrls) - 1)
                        class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                <button wire:click="closeImageModal()"
                    class="absolute top-3 right-3 text-white bg-black bg-opacity-30 rounded-full p-1.5 hover:bg-opacity-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif
</div>
