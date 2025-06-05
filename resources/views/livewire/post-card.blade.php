<article class="p-4 transition-colors bg-white border-b border-gray-200 cursor-pointer dark:bg-gray-900 dark:border-gray-700 hover:bg-gray-50/50 dark:hover:bg-gray-800/50"
         wire:key="post-{{ $post->id }}-{{ $post->updated_at->timestamp }}"> {{-- Unique key for Livewire DOM diffing --}}
    <!-- Post Header -->
    <div class="flex items-start space-x-3">
        <a href="{{ route('profile.show', $post->user->username) }}">
            <img class="object-cover w-10 h-10 rounded-full" src="{{ $post->user->avatarUrl() }}" alt="{{ $post->user->name }}">
        </a>
        <div class="flex-1">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('profile.show', $post->user->username) }}" class="text-sm font-semibold text-gray-800 dark:text-gray-100 hover:underline">{{ $post->user->name }}</a>
                    <span class="ml-1 text-xs text-gray-500 dark:text-gray-400">
                        <a href="{{ route('profile.show', $post->user->username) }}" class="hover:underline">{{ '@'.$post->user->username }}</a>
                        · <time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->diffForHumans() }}</time>
                    </span>
                </div>
                @if(Auth::check() && (Auth::id() === $post->user_id || in_array(Auth::user()->role, ['admin', 'moderator'])))
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="p-1 -mr-1 text-gray-400 rounded-full hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                            <i class="fas fa-ellipsis-h fa-fw"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 z-20 w-48 py-1 mt-2 origin-top-right bg-white rounded-md shadow-lg dark:bg-gray-800 ring-1 ring-black dark:ring-gray-700 ring-opacity-5 focus:outline-none"
                             style="display: none;">
                            @if(Auth::id() === $post->user_id)
                            {{-- <a href="{{ route('posts.edit', $post->id) }}" class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="mr-2 fas fa-edit fa-fw"></i> Edit Post
                            </a> --}}
                            @endif
                            <button wire:click="deletePost" wire:confirm="Are you sure you want to delete this post?"
                                    class="flex items-center w-full px-4 py-2 text-sm text-left text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="mr-2 fas fa-trash-alt fa-fw"></i> Delete Post
                            </button>
                        </div>
                    </div>
                @endif
            </div>
            <!-- Post Content -->
            @if($post->content)
            <div class="mt-1 text-gray-700 dark:text-gray-300 text-[15px] leading-normal prose prose-sm dark:prose-invert max-w-none">
                {!! nl2br(e($post->content)) !!} {{-- Escape and then nl2br for safety --}}
            </div>
            @endif

            <!-- Post Attachments -->
            @if($post->attachments->isNotEmpty())
            <div class="mt-3 overflow-hidden border border-gray-200 rounded-xl dark:border-gray-700">
                {{-- Simplified attachment display for now. You can make this a sub-component later --}}
                @if($post->attachments->count() == 1)
                    @php $attachment = $post->attachments->first(); @endphp
                    @if(Str::startsWith($attachment->file_type, 'image'))
                        <a href="{{ $attachment->fileUrl() }}" target="_blank" data-fancybox="post-{{$post->id}}">
                            <img src="{{ $attachment->fileUrl() }}" alt="Post attachment" class="w-full object-cover max-h-[60vh] bg-gray-200 dark:bg-gray-700">
                        </a>
                    @elseif(Str::startsWith($attachment->file_type, 'video'))
                        <video controls class="w-full max-h-[60vh] bg-black" preload="metadata">
                            <source src="{{ $attachment->fileUrl() }}" type="{{ $attachment->file_type }}">
                            Your browser does not support the video tag.
                        </video>
                    @else
                         <div class="p-3 text-sm bg-gray-100 dark:bg-gray-800">
                            <a href="{{ $attachment->fileUrl() }}" target="_blank" download class="flex items-center text-indigo-600 dark:text-indigo-400 hover:underline">
                                <i class="mr-2 fas fa-paperclip fa-fw"></i> {{ $attachment->fileName() }}
                            </a>
                        </div>
                    @endif
                @else
                    {{-- Basic Grid for multiple images --}}
                    <div class="grid grid-cols-2 gap-0.5"> {{-- gap-0.5 or gap-1 --}}
                        @foreach($post->attachments->take(4) as $attachment)
                            <div class="relative bg-gray-200 aspect-square dark:bg-gray-700">
                                @if(Str::startsWith($attachment->file_type, 'image'))
                                 <a href="{{ $attachment->fileUrl() }}" target="_blank" data-fancybox="post-{{$post->id}}">
                                    <img src="{{ $attachment->fileUrl() }}" alt="Post attachment" class="absolute inset-0 object-cover w-full h-full">
                                 </a>
                                @elseif(Str::startsWith($attachment->file_type, 'video'))
                                    <a href="{{ $attachment->fileUrl() }}" target="_blank" class="absolute inset-0 flex items-center justify-center w-full h-full text-white bg-black">
                                        <i class="opacity-75 fas fa-play-circle fa-3x"></i>
                                    </a>
                                @else
                                    <a href="{{ $attachment->fileUrl() }}" target="_blank" download class="absolute inset-0 flex flex-col items-center justify-center w-full h-full p-2 text-xs text-center text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                                        <i class="mb-1 fas fa-file-alt fa-2x"></i>
                                        <span class="truncate">{{ $attachment->fileName() }}</span>
                                    </a>
                                @endif
                                @if($loop->iteration == 4 && $post->attachments->count() > 4)
                                    <a href="{{ route('posts.show', $post) }}" class="absolute inset-0 flex items-center justify-center text-2xl font-bold text-white bg-black bg-opacity-60 hover:bg-opacity-70">
                                        +{{ $post->attachments->count() - 4 }}
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @endif

            <!-- Post Actions -->
            <div class="flex items-center justify-between mt-3 -ml-2 text-gray-500 dark:text-gray-400">
                <button wire:click="toggleLike" class="flex items-center space-x-1.5 p-2 rounded-full hover:bg-red-500/10 {{ $isLikedByCurrentUser ? 'text-red-500' : 'hover:text-red-500' }}">
                    <i class="{{ $isLikedByCurrentUser ? 'fas' : 'far' }} fa-heart fa-fw text-base"></i>
                    <span class="text-xs font-medium tabular-nums">{{ $likesCount }}</span>
                </button>
                <a href="{{ route('posts.show', $post) }}#comments-section" class="flex items-center space-x-1.5 p-2 rounded-full hover:bg-blue-500/10 hover:text-blue-500">
                    <i class="text-base far fa-comment fa-fw"></i>
                    <span class="text-xs font-medium tabular-nums">{{ $commentsCount }}</span>
                </a>
                <button class="flex items-center space-x-1.5 p-2 rounded-full hover:bg-green-500/10 hover:text-green-500">
                    <i class="text-base fas fa-retweet fa-fw"></i>
                    {{-- <span class="text-xs">Share</span> --}}
                </button>
                <button class="p-2 rounded-full hover:bg-sky-500/10 hover:text-sky-500">
                    <i class="text-base far fa-bookmark fa-fw"></i>
                </button>
            </div>
        </div>
    </div>
</article>
