<!-- Main container:
    - h-screen: Takes full viewport height.
    - bg-gray-50 dark:bg-slate-900: Sets a base background for the entire page, making the chat panel stand out.
-->
<div x-data="{
        chatArea: null,
        init() {
            this.chatArea = this.$refs.chatArea;
            this.scrollToBottom(); // Scroll on initial load
        },
        scrollToBottom() {
            if (!this.chatArea) return;
            // Use a slight delay to ensure DOM is fully updated after Livewire renders
            setTimeout(() => {
                this.chatArea.scrollTop = this.chatArea.scrollHeight;
            }, 50);
        }
    }" x-init="init();" @chat-loaded.window="scrollToBottom()" @message-sent.window="scrollToBottom()"
    class="flex flex-col h-screen bg-gray-50 dark:bg-slate-950">
    @if($selectedConversation)
        <!-- Chat Header:
                                - Added a subtle bottom shadow for depth.
                                - Back button is now always visible (removed md:hidden).
                                - Increased font weight for the name.
                            -->
        <header
            class="flex items-center flex-shrink-0 px-1 py-4 bg-white border-b border-gray-200 shadow-sm dark:border-slate-700 dark:bg-slate-950">
            <a href={{ route('messages') }} wire:navigate
                class="p-2 mr-3 text-gray-500 transition-colors rounded-full hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-slate-700">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <div class="relative">
                <img src="{{ $selectedConversation->avatarUrl() }}" alt="{{ $selectedConversation->name }}"
                    class="object-cover w-10 h-10 border-2 border-gray-300 rounded-full dark:border-gray-600">
                <span
                    class="absolute bottom-0 right-0 block w-3 h-3 bg-green-500 border-2 border-white rounded-full dark:border-slate-800"></span>
            </div>
            <div class="ml-3">
                <h2 class="font-bold text-gray-800 text-md dark:text-gray-100">{{ $selectedConversation->name }}</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Online</p>
            </div>
        </header>

        <!-- Messages Area:
                                - Increased padding for better spacing.
                                - Redesigned message bubbles for a modern look.
                            -->
        <div wire:poll.5s x-ref="chatArea" class="flex-1 p-2 pt-6 overflow-y-auto no-scrollbar">
            <div class="space-y-4">
                @foreach($this->chatMessages as $index => $message)
                    <div wire:key="{{ $message->id }}"
                        class="flex items-end gap-3 @if($message->sender_id === auth()->id()) flex-row-reverse @endif">

                        <!-- Avatar -->
                        <img src="{{ $message->sender_id === auth()->id() ? auth()->user()->avatarUrl() : $selectedConversation->avatarUrl() }}"
                            class="h-8 w-8 rounded-full object-cover flex-shrink-0 border  @if($index > 0 && $this->chatMessages[$index - 1]->sender_id === $message->sender_id) opacity-0 @endif"
                            alt="Avatar">

                        <!-- Message bubble and actions -->
                        <div x-data="{ menuOpen: false }" @mouseover="menuOpen = true" @mouseleave="menuOpen = false"
                            class="relative flex items-center max-w-sm group lg:max-w-lg">

                            <!-- Delete button for user's own messages -->
                            @if($message->sender_id === auth()->id())
                                <div x-show="menuOpen" x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 transform scale-90"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="opacity-100 transform scale-100"
                                    x-transition:leave-end="opacity-0 transform scale-90"
                                    class="absolute flex items-center -translate-y-1/2 top-1/2 -left-10" style="display: none;">
                                    <button wire:click="deleteMessage({{ $message->id }})" wire:confirm="Are you sure?"
                                        class="p-1 text-gray-400 transition-colors bg-white rounded-full shadow-sm hover:text-red-500 dark:bg-slate-700 dark:hover:text-red-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            <!-- Message bubble with content and time -->
                            <div
                                class="px-4 py-2 text-sm rounded-2xl @if($message->sender_id === auth()->id()) rounded-br-lg bg-blue-500 text-white @else rounded-bl-lg bg-white text-gray-800 dark:bg-slate-700 dark:text-gray-200 shadow-sm @endif">
                                <p class="leading-relaxed">{{ $message->message }}</p>

                                <!-- Timestamp and Read Receipt -->
                                <div class="flex items-center justify-end mt-1">
                                    <p
                                        class="text-xs @if($message->sender_id === auth()->id()) text-blue-100/70 @else text-gray-400 dark:text-slate-400 @endif">
                                        {{ $message->created_at->format('g:i A') }}
                                    </p>

                                    <!-- This is the NEW read receipt logic -->
                                    @if($message->sender_id === auth()->id())
                                        <div class="flex-shrink-0">
                                            @if($message->is_read)
                                                {{-- Double check (read) --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-400"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <!-- First check -->
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 12.5l4 4L14 7" />
                                                    <!-- Second check, slightly offset to the right -->
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 12.5l4 4L17 7" />
                                                </svg>

                                            @else
                                                {{-- Single check (sent) --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 text-gray-400 w-45 dark:text-gray-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                            @endif


                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Input Area:
                                - Added a subtle top shadow for separation.
                                - Polished input and button styles.
                            -->
        <footer
            class="flex-shrink-0 p-4 bg-white border-t border-gray-200 dark:bg-slate-800 dark:border-slate-700 shadow-[0_-1px_4px_rgba(0,0,0,0.05)]">
            <form wire:submit.prevent="sendMessage" class="flex items-center gap-3">
                <input type="text" wire:model="newMessage" placeholder="Type a message..." autocomplete="off"
                    class="flex-grow w-full px-4 py-2 text-sm text-gray-900 bg-gray-100 border border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400 dark:focus:ring-offset-slate-800" />
                <button type="submit"
                    class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-white transition-colors bg-blue-500 rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed dark:focus:ring-offset-slate-800"
                    :disabled="$wire.newMessage.trim() === ''">
                    <span wire:loading.remove wire:target="sendMessage">
                        <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </span>
                    <span wire:loading wire:target="sendMessage">
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                            </path>
                        </svg>
                    </span>
                </button>
            </form>
        </footer>
    @else
        <!-- Empty State:
                                - This is shown when no conversation is selected.
                                - It now correctly fills the screen.
                            -->
        <header
            class="flex items-center flex-shrink-0 px-1 py-4 bg-white border-b border-gray-200 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <button onclick="history.back()"
                class="p-2 mr-3 text-gray-500 transition-colors rounded-full hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-slate-700">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
        </header>

        <div class="flex flex-col items-center justify-center flex-1 h-full text-center text-gray-500">
            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">Select a conversation</h3>
            <p class="mt-1 text-sm text-gray-500">Choose from your existing conversations to start chatting.</p>
        </div>
    @endif
</div>
