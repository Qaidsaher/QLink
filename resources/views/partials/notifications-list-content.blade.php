{{-- resources/views/livewire/partials/notifications-list-content.blade.php --}}
@php
    $isFullPage = $isFullPage ?? true; // Default to false if not passed
@endphp

<div class="{{ !$isFullPage ? 'overflow-hidden bg-white rounded-lg shadow-xl dark:bg-slate-800 ring-1 ring-black ring-opacity-5' : '' }}">
    {{-- Panel Header --}}
    <div class="px-4 py-3 border-b border-gray-200 sm:px-6 dark:border-slate-700">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-slate-100">
                {{ $isFullPage ? 'All Notifications' : 'Notifications' }}
            </h3>
            @if ($unreadCount > 0 && Auth::check()) {{-- Show only if logged in and has unread --}}
                <button wire:click="markAllAsRead" wire:loading.attr="disabled"
                    class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 focus:outline-none disabled:opacity-50">
                    Mark all as read
                </button>
            @endif
        </div>
    </div>

    {{-- Tabs for Full Page Mode --}}
    @if ($isFullPage && Auth::check())
        <div class="px-4 pt-3 border-b border-gray-200 sm:px-6 dark:border-slate-700">
            <nav class="flex -mb-px space-x-6" aria-label="Tabs">
                <button wire:click="setStatusFilter(null)"
                    class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm transition-colors {{ is_null($statusFilter) ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-300' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:border-slate-600' }}">
                    All
                </button>
                <button wire:click="setStatusFilter('unread')"
                    class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm transition-colors {{ $statusFilter === 'unread' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-300' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:border-slate-600' }}">
                    Unread <span class="text-xs {{ $statusFilter === 'unread' ? 'text-blue-500' : 'text-gray-400' }}">({{ $unreadCount }})</span>
                </button>
                <button wire:click="setStatusFilter('read')"
                    class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm transition-colors {{ $statusFilter === 'read' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-300' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:border-slate-600' }}">
                    Read
                </button>
            </nav>
        </div>
    @endif

    {{-- Notifications List --}}
    @if (!Auth::check())
        <div class="p-6 text-sm text-center text-gray-500 dark:text-slate-400">
            Please <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">login</a> to see your notifications.
        </div>
    @elseif ($notifications->isEmpty() && (($isFullPage) ? !$notifications->hasPages() : !$hasMoreNotifications))
        <div class="p-8 text-sm text-center text-gray-500 dark:text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" />
            </svg>
            @if ($isFullPage && !is_null($statusFilter))
                No {{ $statusFilter }} notifications found.
            @else
                You have no notifications right now.
            @endif
        </div>
    @else
        <div class="{{ !$isFullPage ? 'overflow-y-auto max-h-96' : '' }}">
            <ul role="list" class="divide-y divide-gray-200 dark:divide-slate-700">
                @foreach ($notifications as $notification)
                    <li wire:key="{{ ($isFullPage ? 'page-' : 'panel-') }}{{ $notification->id }}"
                        class="p-4 transition-colors hover:bg-gray-50 dark:hover:bg-slate-700/50 {{ is_null($notification->read_at) ? ($isFullPage ? 'bg-white dark:bg-slate-800' : 'bg-blue-50 dark:bg-blue-900/20') : ($isFullPage ? 'bg-gray-50 dark:bg-slate-800/60 opacity-80' : 'opacity-80') }}">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                @if ($notification->avatar)
                                    <img class="w-10 h-10 rounded-full" src="{{ $notification->avatar }}" alt="">
                                @else
                                    <span class="flex items-center justify-center w-10 h-10 text-white rounded-full {{ $notification->type_color ?? 'bg-gray-400' }}">
                                        <span class="text-lg">{{ $notification->type_icon ?? '🔔' }}</span>
                                    </span>
                                @endif
                                @if(is_null($notification->read_at))
                                    <span class="block w-2.5 h-2.5 bg-blue-500 rounded-full mt-1.5 mx-auto" title="Unread"></span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm text-gray-800 dark:text-slate-200 {{ is_null($notification->read_at) ? 'font-semibold' : 'font-normal' }}">
                                    {!! $notification->message !!}
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <p class="text-xs text-gray-500 dark:text-slate-400">
                                        {{ $notification->timestamp }}
                                    </p>
                                    @if (is_null($notification->read_at))
                                        <button wire:click="markAsRead('{{ $notification->id }}')"
                                            title="Mark as read"
                                            class="p-1 text-xs text-blue-600 rounded-md dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-700 focus:outline-none">
                                            Mark Read
                                        </button>
                                    @else
                                        <span class="text-xs text-green-600 dark:text-green-500">Read</span>
                                    @endif
                                </div>
                            </div>
                            @if ($notification->link && $notification->link !== '#')
                            <a href="{{ $notification->link }}" @if(!$isFullPage) @click="isOpen = false" @endif class="self-center flex-shrink-0 ml-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
            {{-- "Load More" button for dropdown mode --}}
            @if (!$isFullPage && $hasMoreNotifications && $notifications->isNotEmpty())
                <div class="p-2 text-center">
                    <button wire:click="loadMore" wire:loading.attr="disabled" wire:target="loadMore"
                        class="w-full px-3 py-2 text-xs font-medium text-blue-600 rounded-md dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-slate-700 focus:outline-none disabled:opacity-50">
                        <span wire:loading wire:target="loadMore" class="inline-block w-3 h-3 mr-1 border-2 border-current rounded-full animate-spin border-t-transparent"></span>
                        Load More
                    </button>
                </div>
            @endif
        </div>
    @endif

    {{-- "View all notifications" link for dropdown mode --}}
    @if (!$isFullPage)
        <div class="px-4 py-3 bg-gray-50 dark:bg-slate-800/50 sm:px-6">
            <a href="{{ route('notifications') }}"
               @click="isOpen = false"
               class="block w-full text-sm font-medium text-center text-blue-600 rounded-md dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-slate-700 py-1.5">
                View all notifications
            </a>
        </div>
    @endif
</div>