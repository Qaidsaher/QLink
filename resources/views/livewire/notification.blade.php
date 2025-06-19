<div>
    <div class="p-6">


        <!-- Tabs -->
        <div class="flex items-center justify-between mt-1 border-b border-gray-200 dark:border-gray-700">
            <nav class="flex -mb-px space-x-6" aria-label="Tabs">
                <!-- All Tab -->
                <button wire:click="setFilter('all')" class="shrink-0 px-1 py-4 text-sm font-medium border-b-2
                    {{ $filter === 'all' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-500' }}">
                    All
                    <span class="ml-1.5 rounded-full px-2 py-0.5 text-xs font-semibold
                        {{ $filter === 'all' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $this->allCount }}
                    </span>
                </button>

                <!-- Unread Tab -->
                <button wire:click="setFilter('unread')" class="shrink-0 px-1 py-4 text-sm font-medium border-b-2
                    {{ $filter === 'unread' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-500' }}">
                    Unread
                    <span class="ml-1.5 rounded-full px-2 py-0.5 text-xs font-semibold
                        {{ $filter === 'unread' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $this->unreadCount }}
                    </span>
                </button>

                <!-- NEW: Read Tab -->
                <button wire:click="setFilter('read')" class="shrink-0 px-1 py-4 text-sm font-medium border-b-2
                    {{ $filter === 'read' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-500' }}">
                    Read
                    <span class="ml-1.5 rounded-full px-2 py-0.5 text-xs font-semibold
                        {{ $filter === 'read' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $this->readCount }}
                    </span>
                </button>
            </nav>
             @if($this->unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                    Mark all as read
                </button>
            @endif
        </div>

        <!-- Notification List -->
        <div wire:loading.class.delay="opacity-50" class="flow-root mt-5">
            <div class="-my-5 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($this->notifications as $notification)
                    <div wire:key="{{ $notification->id }}" class="relative py-5 group">
                        <div class="flex items-start space-x-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <!-- UPDATED: Added ring classes for the border effect -->
                                <span class="h-10 w-10 rounded-full flex items-center justify-center text-xl text-white ring-2 ring-white dark:ring-gray-700 {{ $notification->data['color'] }}">
                                    {{ $notification->data['icon'] }}
                                </span>
                            </div>

                            <!-- Content (No changes here, but included for context) -->
                            <div class="flex-1 min-w-0">
                                <a @if($notification->data['url']) wire:click.prevent="markAsReadAndRedirect('{{ $notification->id }}')" href="{{ $notification->data['url'] }}" @else href="#" @endif
                                   class="block focus:outline-none">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        @if(is_null($notification->read_at))
                                            <span class="inline-block w-2 h-2 mr-2 bg-indigo-500 rounded-full" title="Unread"></span>
                                        @endif
                                        <span class="font-bold">{{ $notification->data['username'] }}</span>
                                        {{ \Illuminate\Support\Str::of($notification->data['message'])->after($notification->data['username']) }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </a>
                            </div>

                            <!-- Actions Dropdown (No changes here) -->
                            <div x-data="{ open: false }" class="relative flex-shrink-0">
                                <!-- ... -->
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- ... Empty state remains the same ... -->
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if ($this->notifications->hasPages())
            <div class="px-2 py-2 mt-6 border-t border-gray-200 dark:border-gray-700">
                {{ $this->notifications->links() }}
            </div>
        @endif
    </div>
</div>
