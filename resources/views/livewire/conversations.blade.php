<!--
    Main container for the conversation list.
    - Full height and a distinct background color.
    - A right border to visually separate it from the chat panel on larger screens.
-->
<div class="min-h-screen bg-white border-r border-gray-200 dark:bg-slate-950 dark:border-slate-700">
    <div class="flex flex-col h-full ">
        <!-- Header with a "New Message" icon -->
        <header
            class="flex items-center justify-between flex-shrink-0 p-4 border-b border-gray-200 dark:border-slate-700">
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Messages</h1>
            <button
                class="p-2 text-gray-500 transition-colors rounded-full hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </button>
        </header>

        <!-- Search Bar Area -->
        <div class="flex-shrink-0 p-4">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </svg>
                </span>
                <input wire:model.live.debounce.300ms="searchTerm" type="text" placeholder="Search or start new chat"
                    class="w-full py-2 pl-10 pr-4 text-sm text-gray-800 transition-colors bg-gray-100 border border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400 dark:focus:ring-offset-slate-800">
            </div>
        </div>

        <!-- Conversation List with polling -->
        <div wire:poll.10s class="flex-1 px-2 overflow-y-auto no-scrollbar">
            @if(!empty(trim($searchTerm)))
                <p class="px-2 pt-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">Search Results</p>
                @forelse($this->searchResults as $user)
                    <div wire:key="search-{{ $user->id }}" class="py-1">
                        <a href="{{ route('chat', ['user' => $user->id]) }}" wire:navigate class="block">
                            <button
                                class="flex items-center w-full p-3 text-left transition-colors duration-200 rounded-md hover:bg-gray-100 focus:outline-none dark:hover:bg-slate-700">
                              <x-avatar :user="$user"  />
                                <div class="flex-grow ml-4">
                                    <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $user->name }}</h2>
                                    <p class="text-xs text-gray-500 truncate dark:text-gray-400">Start a new conversation</p>
                                </div>
                            </button>
                        </a>
                    </div>
                @empty
                    <p class="p-4 text-sm text-center text-gray-500">No users found for "{{ $searchTerm }}".</p>
                @endforelse
            @else
                <p class="px-2 pt-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">Conversations </p>
                @forelse($conversations as $conversation)
                    <div wire:key="conv-{{ $conversation->id }}" class="py-1">
                        <a href="{{ route('chat', ['user' => $conversation->id]) }}" wire:navigate class="block">
                            <button
                                class="flex items-center w-full p-3 text-left transition-colors duration-200 rounded-md focus:outline-none hover:bg-gray-100 dark:hover:bg-slate-700 ">
                                <div class="relative flex-shrink-0">
                                     <x-avatar :user="$conversation"  />
                                </div>
                                <div class="flex-grow min-w-0 ml-4">
                                    <h2 class="text-sm font-semibold text-gray-800 truncate dark:text-gray-200">
                                        {{ $conversation->name }}
                                    </h2>
                                    <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                                        {{ $conversation->last_message }}
                                    </p>
                                </div>
                                <div class="flex flex-col items-end flex-shrink-0 ml-2 space-y-1">
                                    <span
                                        class="text-xs text-gray-400">{{ $conversation->last_message_at?->shortAbsoluteDiffForHumans() }}</span>
                                    @if($conversation->unread_count > 0)
                                        <span
                                            class="flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-blue-500 rounded-full">{{ $conversation->unread_count }}</span>
                                    @endif
                                </div>
                            </button>
                        </a>

                    </div>
                @empty
                    <p class="p-4 text-sm text-center text-gray-500">No conversations yet. Search for someone to start chatting.
                    </p>
                @endforelse
            @endif
        </div>
    </div>

</div>
