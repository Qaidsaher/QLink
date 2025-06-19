<div class="max-w-5xl mx-auto">
    <!-- Cover Photo Area -->
    <div class="relative h-48 overflow-hidden bg-slate-200 dark:bg-slate-800 sm:h-64">
          <a href="javascript:history.back()" wire:wire:navigate class="absolute flex items-center px-3 py-1 space-x-1 bg-white rounded shadow top-2 left-2 text-slate-700 dark:bg-slate-900 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </a>
                {{-- <img src="{{ $user->cover_photo_url }}" alt="Cover Photo" class="object-cover w-full h-full"> --}}
    </div>

    <!-- Profile Header Section -->
    <div class="px-4 -mt-16 sm:px-6 md:-mt-20">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <!-- Avatar and Name -->
            <div class="flex items-end gap-4">
                <div class="relative flex-shrink-0 p-1 bg-white rounded-full dark:bg-slate-900">
                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="object-cover rounded-full w-28 h-28 sm:w-32 sm:h-32">
                </div>
                <div class="pb-2">
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">@<span>{{ $user->username }}</span></p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex-shrink-0 pb-2">
                @auth
                    @if(Auth::id() === $user->id)
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center justify-center w-full px-5 py-2 text-sm font-semibold bg-white border rounded-lg shadow-sm text-slate-700 dark:text-slate-200 dark:bg-slate-800 border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 sm:w-auto">
                            <i class="w-4 h-4 mr-2 fas fa-pencil-alt"></i>Edit Profile
                        </a>
                    @else
                        @livewire('follow-button', ['userIdToFollow' => $user->id], key('follow-btn-profile-' . $user->id))
                    @endif
                @endauth
            </div>
        </div>

        <!-- Bio and Stats -->
        <div class="mt-4 space-y-4">
             @if($user->bio)
                <p class="max-w-2xl text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ $user->bio }}</p>
            @endif
            <div class="flex items-center gap-6 text-sm">
                <div class="flex items-center text-slate-500 dark:text-slate-400">
                    <i class="w-4 h-4 mr-2 fa-solid fa-calendar-alt"></i>
                    <span>Joined {{ $user->created_at->format('F Y') }}</span>
                </div>
                <button wire:click="switchTab('following')" class="text-slate-500 dark:text-slate-400 hover:underline">
                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ $user->following_count }}</span> Following
                </button>
                <button wire:click="switchTab('followers')" class="text-slate-500 dark:text-slate-400 hover:underline">
                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ $user->followers_count }}</span> Followers
                </button>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="px-4 mt-6 border-b sm:px-0 border-slate-200 dark:border-slate-700">
        <nav class="flex -mb-px space-x-6">
            <button wire:click="switchTab('posts')" class="px-1 py-3 text-sm font-semibold border-b-2 transition-colors {{ $activeTab === 'posts' ? 'border-blue-500 text-blue-500' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }}">Posts</button>
            <button wire:click="switchTab('about')" class="px-1 py-3 text-sm font-semibold border-b-2 transition-colors {{ $activeTab === 'about' ? 'border-blue-500 text-blue-500' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }}">About</button>
        </nav>
    </div>

    <!-- Main Content Area based on Active Tab -->
    <div class="p-2 mt-6">
        @if ($activeTab === 'posts')
            @if($postsForProfile->isNotEmpty())
                <div class="space-y-6">
                    @foreach ($postsForProfile as $post)
                        <article wire:key="profile-post-{{ $post->id }}" class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700/50">
                            <div class="p-4 sm:p-5">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center">
                                        {{-- CORRECTED ROUTE --}}
                                        <a href="{{ route('profile.show', ['user' => $post->user->username]) }}">
                                            <img src="{{ $post->user->avatarUrl() }}" alt="{{ $post->user->name }}" class="object-cover w-10 h-10 mr-3 transition-all rounded-full hover:ring-2 hover:ring-indigo-500 dark:hover:ring-indigo-400">
                                        </a>
                                        <div>
                                            {{-- CORRECTED ROUTE --}}
                                            <a href="{{ route('profile.show', ['user' => $post->user->username]) }}" class="font-semibold text-gray-900 dark:text-white hover:underline">{{ $post->user->name }}</a>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 text-sm leading-relaxed prose text-gray-700 dark:text-gray-300 dark:prose-invert max-w-none">
                                    {!! nl2br(e($post->content)) !!}
                                </div>
                                @if($post->attachments->isNotEmpty())
                                    <div class="mt-3 text-xs">
                                        @foreach($post->attachments->take(2) as $attachment)
                                            <span class="inline-block bg-gray-100 dark:bg-gray-700 rounded-full px-2 py-0.5 text-gray-600 dark:text-gray-300 mr-1 mb-1">
                                                📎 {{ Str::limit($attachment->file_name ?? 'attachment', 20) }}
                                            </span>
                                        @endforeach
                                        @if($post->attachments->count() > 2)
                                         <span class="text-gray-500 dark:text-gray-400">(+{{ $post->attachments->count() - 2 }} more)</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center justify-around p-2 text-xs border-t border-gray-200 dark:border-gray-700/50">
                                <button wire:click="toggleLike({{ $post->id }})" wire:loading.attr="disabled" wire:target="toggleLike({{ $post->id }})"
                                        class="flex items-center space-x-1 p-1.5 rounded-md transition-colors {{ $post->is_liked ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="{{ $post->is_liked ? 'currentColor' : 'none' }}" stroke="currentColor"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>
                                    <span>{{ $post->likes_count ?? $post->likes()->count() }}</span>
                                </button>
                                <button wire:click="toggleCommentsSection({{ $post->id }})" class="flex items-center space-x-1 p-1.5 rounded-md text-gray-500 dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zm-4 0H9v2h2V9z" clip-rule="evenodd" /></svg>
                                    <span>{{ $post->comments_count ?? $post->comments()->count() }}</span>
                                </button>
                            </div>
                            @if($openCommentsSection[$post->id] ?? false)
                            <div class="p-3 border-t border-gray-200 dark:border-gray-700/50 bg-gray-50 dark:bg-gray-800/60">
                                {{-- Comment Form and List --}}
                                @auth
                                <div class="flex items-start mb-2 space-x-2">
                                    <img src="{{ Auth::user()->avatar_url }}" alt="Your avatar" class="object-cover rounded-full w-7 h-7">
                                    <input type="text" wire:model.defer="newCommentText.{{ $post->id }}" placeholder="Write a comment..."
                                           wire:keydown.enter="addComment({{ $post->id }})"
                                           class="w-full text-xs p-1.5 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-1 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200">
                                </div>
                                @endauth
                                @foreach($post->comments->take(3) as $comment)
                                    <div class="flex items-start space-x-1.5 mt-1.5 text-xs">
                                        <img src="{{ $comment->user->avatarUrl() }}" alt="{{ $comment->user->name }}" class="flex-shrink-0 object-cover w-6 h-6 rounded-full">
                                        <div class="bg-gray-100 dark:bg-gray-700 p-1.5 rounded-lg">
                                            {{-- CORRECTED ROUTE --}}
                                            <a href="{{ route('profile.show', ['user' => $comment->user->username]) }}" class="font-semibold text-gray-800 dark:text-gray-200 hover:underline">{{ $comment->user->name }}</a>
                                            <span class="ml-1 text-gray-600 dark:text-gray-300">{{ $comment->content }}</span>
                                        </div>
                                    </div>
                                @endforeach
                                @if(($post->comments_count ?? $post->comments()->count()) > 3)
                                    <button class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-1.5">View all {{ $post->comments_count ?? $post->comments()->count() }} comments</button>
                                @endif
                            </div>
                            @endif
                        </article>
                    @endforeach
                    @if ($postsForProfile->hasPages())
                        <div class="py-4">
                            {{ $postsForProfile->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="py-12 text-center bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.79 4 4s-1.79 4-4 4c-1.742 0-3.223-.835-3.772-2M10 12h4M4.634 15.332A8.959 8.959 0 012.25 12c0-4.953 4.021-8.978 8.977-8.978 4.957 0 8.978 4.025 8.978 8.978 0 2.463-1.008 4.71-2.659 6.355m-4.44-2.02A5.973 5.973 0 0012.001 18c-1.592 0-3.044-.593-4.168-1.569" /></svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No posts to show</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This user hasn't posted anything yet.</p>
                </div>
            @endif

        @elseif ($activeTab === 'about')
            <div class="p-6 bg-white border rounded-lg border-slate-200 dark:bg-slate-800 dark:border-slate-700">
                <h3 class="pb-3 mb-6 text-xl font-semibold border-b text-slate-800 dark:text-white dark:border-slate-700">About {{ $user->name }}</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                    <div><dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Name</dt><dd class="mt-1 text-base text-slate-900 dark:text-white">{{ $user->name }}</dd></div>
                    <div><dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Username</dt><dd class="mt-1 text-base text-slate-900 dark:text-white">@<span>{{ $user->username }}</span></dd></div>
                    @if($user->email && (Auth::check() && Auth::id() === $user->id))
                    <div><dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Email</dt><dd class="mt-1 text-base text-slate-900 dark:text-white">{{ $user->email }}</dd></div>
                    @endif
                     <div><dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Joined</dt><dd class="mt-1 text-base text-slate-900 dark:text-white">{{ $user->created_at->format('F j, Y') }}</dd></div>
                    @if($user->bio)
                    <div class="md:col-span-2"><dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Bio</dt><dd class="mt-1 text-base leading-relaxed prose dark:prose-invert max-w-none text-slate-900 dark:text-white">{!! nl2br(e($user->bio)) !!}</dd></div>
                    @endif
                </dl>
            </div>

        @elseif ($activeTab === 'followers' || $activeTab === 'following')
            @php
                $listItems = ($activeTab === 'followers') ? $followersList : $followingList;
                $listTitle = ($activeTab === 'followers') ? 'Followers' : 'Following';
            @endphp
            <div class="bg-white border rounded-lg border-slate-200 dark:bg-slate-800 dark:border-slate-700">
                <h3 class="px-5 py-4 text-lg font-semibold border-b text-slate-800 dark:text-white dark:border-slate-700">{{ $listTitle }}</h3>
                @if($listItems->isNotEmpty())
                    <ul role="list" class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($listItems as $listItemUser)
                            <li class="flex items-center justify-between gap-4 px-5 py-4 transition-colors hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <div class="flex items-center min-w-0 gap-4">
                                    <a href="{{ route('profile.show', ['user' => $listItemUser->username]) }}" class="flex-shrink-0">
                                        <img class="object-cover w-12 h-12 rounded-full" src="{{ $listItemUser->avatarUrl() }}" alt="{{ $listItemUser->name }}">
                                    </a>
                                    <div class="min-w-0">
                                        <p class="font-semibold truncate text-slate-800 dark:text-white">
                                            <a href="{{ route('profile.show', ['user' => $listItemUser->username]) }}" class="hover:underline">{{ $listItemUser->name }}</a>
                                        </p>
                                        <p class="text-sm truncate text-slate-500 dark:text-slate-400">@<span>{{ $listItemUser->username }}</span></p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    @auth
                                        @if(Auth::id() !== $listItemUser->id)
                                            @livewire('follow-button', ['userIdToFollow' => $listItemUser->id, 'isSmall' => true], key('follow-btn-' . $this->activeTab . '-' . $listItemUser->id))
                                        @endif
                                    @endauth
                                </div>
                            </li>
                        @endforeach
                    </ul>
                     @if ($listItems->hasPages())
                        <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                            {{ $listItems->links() }}
                        </div>
                    @endif
                @else
                     <p class="p-12 text-sm text-center text-slate-500 dark:text-slate-400">This list is empty.</p>
                @endif
            </div>
        @endif
    </div>

</div>
