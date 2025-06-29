<div>
    {{-- Sticky Header for Profile Page --}}
    <header
        class="sticky top-0 z-30 border-b border-gray-200 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm dark:border-slate-700">
        <div class="flex items-center gap-4 px-2 py-2">
            <a onclick="window.history.back()" wire:navigate
                class="p-2 transition-colors rounded-full hover:bg-gray-200 dark:hover:bg-slate-800">
                <svg class="w-5 h-5 text-gray-800 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->posts_count }}
                    {{ Str::plural('Post', $user->posts_count) }}</p>
            </div>
        </div>
    </header>

    {{-- Main Profile Content --}}
    <div class="max-w-5xl mx-auto">
        <!-- Cover Photo -->
        <div class="relative h-48 bg-slate-200 dark:bg-slate-800 sm:h-64">
            @if($user->BackgroundImageCover())
                <img src="{{ $user->BackgroundImageCover() }}" alt="Cover Photo" class="object-cover w-full h-full">
            @endif
        </div>

        <!-- Profile Details Section -->
        <div class="px-4 -mt-16 sm:px-6 md:-mt-20">
            {{-- Action Buttons & Avatar --}}
            <div class="flex items-end justify-between">
                <div class="relative flex-shrink-0 p-1 bg-white rounded-full dark:bg-slate-900">
                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                        class="object-cover border-2 border-white rounded-full w-28 h-28 sm:w-36 sm:h-36 dark:border-slate-900">
                </div>
                <div class="flex items-center gap-2 pb-2">
                    @auth
                        @if(Auth::id() === $user->id)
                            <a href="{{ route('profile.edit') }}" wire:navigate
                                class="px-4 py-1.5 text-sm font-bold bg-transparent border border-gray-300 dark:border-gray-600 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800">
                                Edit Profile
                            </a>
                        @else
                            @livewire('follow-button', ['userIdToFollow' => $user->id], key('follow-btn-profile-' . $user->id))
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Name, Username, Bio, and Stats --}}
            <div class="mt-4 space-y-3">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">@<span>{{ $user->username }}</span></p>
                </div>
                @if($user->bio)
                    <p class="max-w-2xl text-base leading-relaxed text-slate-700 dark:text-slate-300">{{ $user->bio }}</p>
                @endif
                {{-- Location and Website --}}
                @if($user->location || $user->website)
                    <div class="flex flex-wrap items-center text-sm gap-x-4 gap-y-2 text-slate-500 dark:text-slate-400">
                        @if($user->location)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 11.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7-7.5 11.5-7.5 11.5S4.5 17.5 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                <span>{{ $user->location }}</span>
                            </div>
                        @endif

                        @if($user->website)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                <a href="{{ $user->website }}" target="_blank" rel="noopener noreferrer"
                                    class="text-blue-500 hover:underline dark:text-blue-400">
                                    {{ parse_url($user->website, PHP_URL_HOST) ?? $user->website }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                    <div class="flex items-center text-sm text-slate-500 dark:text-slate-400">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>Joined {{ $user->created_at->format('F Y') }}</span>
                    </div>
                    <button wire:click="switchTab('following')"
                        class="text-sm text-slate-500 dark:text-slate-400 hover:underline">
                        <span class="font-bold text-slate-800 dark:text-slate-100">{{ $user->following_count }}</span>
                        Following
                    </button>
                    <button wire:click="switchTab('followers')"
                        class="text-sm text-slate-500 dark:text-slate-400 hover:underline">
                        <span class="font-bold text-slate-800 dark:text-slate-100">{{ $user->followers_count }}</span>
                        Followers
                    </button>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="mt-6 border-b border-slate-200 dark:border-slate-700">
            <nav class="flex justify-around -mb-px" aria-label="Tabs">
                <button wire:click="switchTab('posts')"
                    class="w-full py-4 text-sm font-semibold text-center transition-colors {{ $activeTab === 'posts' ? 'border-b-2 border-blue-500 text-slate-900 dark:text-white' : 'border-transparent text-slate-500 hover:bg-gray-200 dark:text-slate-400 dark:hover:bg-slate-800' }}">Posts</button>
                <button wire:click="switchTab('about')"
                    class="w-full py-4 text-sm font-semibold text-center transition-colors {{ $activeTab === 'about' ? 'border-b-2 border-blue-500 text-slate-900 dark:text-white' : 'border-transparent text-slate-500 hover:bg-gray-200 dark:text-slate-400 dark:hover:bg-slate-800' }}">About</button>
                <button wire:click="switchTab('followers')"
                    class="w-full py-4 text-sm font-semibold text-center transition-colors {{ $activeTab === 'followers' ? 'border-b-2 border-blue-500 text-slate-900 dark:text-white' : 'border-transparent text-slate-500 hover:bg-gray-200 dark:text-slate-400 dark:hover:bg-slate-800' }}">Followers</button>
                <button wire:click="switchTab('following')"
                    class="w-full py-4 text-sm font-semibold text-center transition-colors {{ $activeTab === 'following' ? 'border-b-2 border-blue-500 text-slate-900 dark:text-white' : 'border-transparent text-slate-500 hover:bg-gray-200 dark:text-slate-400 dark:hover:bg-slate-800' }}">Following</button>
            </nav>
        </div>

        <!-- Main Content Area based on Active Tab -->
        <div class="mt-4">
            @if ($activeTab === 'posts')
                @livewire('posts', ['userId' => $user->id], key('user-posts-' . $user->id))
            @elseif ($activeTab === 'about')
                <div class="p-4 space-y-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Name</dt>
                            <dd class="mt-1 text-base text-slate-900 dark:text-white">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Username</dt>
                            <dd class="mt-1 text-base text-slate-900 dark:text-white">@<span>{{ $user->username }}</span>
                            </dd>
                        </div>
                        @if($user->email && Auth::check() && Auth::id() === $user->id)
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Email</dt>
                                <dd class="mt-1 text-base text-slate-900 dark:text-white">{{ $user->email }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Joined</dt>
                            <dd class="mt-1 text-base text-slate-900 dark:text-white">
                                {{ $user->created_at->format('F j, Y') }}</dd>
                        </div>
                    </dl>
                </div>
            @elseif ($activeTab === 'followers' || $activeTab === 'following')
                @php
                    $listItems = ($activeTab === 'followers') ? $followersList : $followingList;
                @endphp
                <div>
                    @if($listItems->isNotEmpty())
                        @foreach($listItems as $listItemUser)
                            <div
                                class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800/50">
                                <div class="flex items-center flex-1 min-w-0 gap-3">
                                    <a href="{{ route('profile.show', $listItemUser->username) }}" wire:navigate><x-avatar
                                            :user="$listItemUser" /></a>
                                    <div class="flex-1 min-w-0 leading-tight">
                                        <a href="{{ route('profile.show', $listItemUser->username) }}" wire:navigate
                                            class="font-bold text-slate-800 dark:text-slate-200 hover:underline">{{ $listItemUser->name }}</a>
                                        <p class="text-sm truncate text-slate-500 dark:text-slate-400">
                                            @<span>{{ $listItemUser->username }}</span></p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ml-2">
                                    @auth @if(Auth::id() !== $listItemUser->id)
                                        @livewire('follow-button', ['userIdToFollow' => $listItemUser->id], key('follow-btn-' . $activeTab . '-' . $listItemUser->id))
                                    @endif @endauth
                                </div>
                            </div>
                        @endforeach
                        @if ($listItems->hasPages())
                            <div class="p-4">{{ $listItems->links() }}</div>
                        @endif
                    @else
                        <p class="p-12 text-sm text-center text-slate-500 dark:text-slate-400">This list is empty.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>