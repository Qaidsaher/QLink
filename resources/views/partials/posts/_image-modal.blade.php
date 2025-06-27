  {{-- Your existing image modal remains unchanged --}}
    @if ($imageModalOpen && !empty($imageModalUrls))
        <div x-data @keydown.escape.window="$wire.closeImageModal()"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-80" x-trap.noscroll="true"
            x-show="$wire.imageModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="$wire.closeImageModal()"
                class="relative flex flex-col w-full p-2 bg-white rounded-lg shadow-xl dark:bg-gray-900 max-w-4xl max-h-[90vh]">
                @if (isset($imageModalUrls[$currentImageModalIndex]))
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
                        <span>{{ $currentImageModalIndex + 1 }}</span> /
                        <span>{{ count($imageModalUrls) }}</span>
                    </p>
                    <button wire:click="navigateImageModal(1)" @disabled($currentImageModalIndex === count($imageModalUrls) - 1)
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