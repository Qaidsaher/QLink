<div>

    @php


        function getActionIcon($action)
        {
            $icons = [
                'post_created' => 'text-blue-500 fas fa-plus-circle',
                'post_updated' => 'text-yellow-500 fas fa-edit',
                'post_deleted' => 'text-red-500 fas fa-trash',
                'post_liked' => 'text-pink-500 fas fa-heart',
                'post_unliked' => 'text-pink-500 fas fa-heart-broken',
                'commented' => 'text-green-500 fas fa-comment',
                'comment_deleted' => 'text-red-400 fas fa-comment-slash',
                'shared_post' => 'text-blue-400 fas fa-share',
                'bookmarked' => 'text-yellow-400 fas fa-bookmark',
                'mentioned' => 'text-purple-500 fas fa-bullhorn',
                'tagged' => 'text-indigo-400 fas fa-tags',
                'followed' => 'text-blue-600 fas fa-user-plus',
                'unfollowed' => 'text-blue-600 fas fa-user-minus',
                'reacted' => 'text-yellow-400 fas fa-smile',
                'sent_message' => 'text-blue-500 fas fa-paper-plane',
                'received_message' => 'text-blue-500 fas fa-inbox',
                'joined_group' => 'text-green-500 fas fa-users',
                'left_group' => 'text-red-500 fas fa-user-times',
                'posted_photo' => 'text-purple-500 fas fa-camera-retro',
                'uploaded_video' => 'text-red-500 fas fa-video',
                'shared_music' => 'text-indigo-500 fas fa-music',
                'shared_link' => 'text-blue-500 fas fa-link',
                'started_poll' => 'text-yellow-500 fas fa-poll',
                'birthday' => 'text-pink-500 fas fa-birthday-cake',
                'received_gift' => 'text-green-500 fas fa-gift',
                'suggested_friends' => 'text-cyan-500 fas fa-user-friends',
                'notification' => 'text-orange-500 fas fa-bell',
                'login' => 'text-green-600 fas fa-sign-in-alt',
                'logout' => 'text-red-600 fas fa-sign-out-alt',
                'register' => 'text-blue-600 fas fa-user-plus',
            ];

            $iconClass = $icons[$action] ?? 'text-gray-500 fas fa-question-circle';

            return '<span class="inline-flex items-center justify-center w-10 h-10 text-xl bg-white border border-gray-300 rounded-full shadow-sm dark:bg-gray-800 dark:border-gray-600">
                                                    <i class="' . $iconClass . '"></i>
                                                </span>';
        }



    @endphp
    {{-- Sticky Header with Title and Tabs --}}
    <div class="">
        {{-- Header --}}
        <header class="sticky top-0 z-10 shadow-sm">
            <div class="flex items-center justify-between p-3 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Notifications</h2>
                <div class="flex items-center space-x-3">
                    @if($this->unreadCount)
                        <button wire:click="markAllAsRead" class="text-sm font-medium text-indigo-600 hover:underline">Mark
                            all as read</button>
                    @endif
                    <button class="p-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700" title="Settings">
                        <i class="text-gray-600 fas fa-cog dark:text-gray-300"></i>
                    </button>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="flex px-3 mt-4 space-x-4 border-b border-gray-200 dark:border-gray-700">
                @foreach(['all', 'unread', 'read'] as $tab)
                    <button wire:click="setFilter('{{ $tab }}')"
                        class="pb-2 text-sm font-medium
                                                   {{ $filter === $tab ? 'border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
                        {{ ucfirst($tab) }}
                    </button>
                @endforeach
            </div>
        </header>

        {{-- List --}}
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($this->notifications as $notification)
                <a wire:key="{{ $notification->id }}" wire:click.prevent="markAsReadAndRedirect('{{ $notification->id }}')"
                    href="{{ $notification->data['url'] ?? '#' }}"
                    class="flex items-start p-4 space-x-4 transition rounded-lg hover:bg-gray-50 dark:hover:bg-gray-900">

                    {{-- Icon --}}
                    {!! getActionIcon($notification->data['action']) !!}

                    {{-- Content --}}
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $notification->data['username'] }}
                                @if(is_null($notification->read_at))
                                    <span
                                        class="inline-block  mx-2 text-xs text-white bg-indigo-500 px-2 py-0.5 rounded-full">New</span>
                                @endif
                            </span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                            {{ \Illuminate\Support\Str::after($notification->data['message'], $notification->data['username']) }}
                        </p>

                    </div>
                </a>
            @empty
                <div class="py-16 text-center text-gray-500 dark:text-gray-400">
                    <p class="text-lg font-medium">No notifications yet</p>
                    <p class="mt-2 text-sm">When new notifications arrive, you’ll see them here.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($this->notifications->hasPages())
            <div class="mt-6">
                {{ $this->notifications->links() }}
            </div>
        @endif

    </div>
</div>