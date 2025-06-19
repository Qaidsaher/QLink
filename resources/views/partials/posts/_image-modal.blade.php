
<div
    x-data
    x-show="$wire.imageModalOpen"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="$wire.closeImageModal()"
    @keydown.arrow-left.window="$wire.navigateImageModal(-1)"
    @keydown.arrow-right.window="$wire.navigateImageModal(1)"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90"

    x-trap.noscroll.inert="$wire.imageModalOpen"

    style="display: none;"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
>
    <!-- Main Modal Content -->
    <div @click.away="$wire.closeImageModal()" class="relative flex flex-col w-full h-full">

        <!-- Close Button (Top Right) -->
        <button wire:click="closeImageModal()"
            class="absolute top-0 right-0 z-20 m-4 text-white rounded-full opacity-70 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-black/50">
            <span class="sr-only">Close</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Image Container -->
        <div class="relative flex items-center justify-center flex-grow w-full h-full" aria-hidden="true">
            @if(isset($imageModalUrls[$currentImageModalIndex]))
                <img src="{{ $imageModalUrls[$currentImageModalIndex]['url'] }}" alt="Enlarged view"
                     class="object-contain w-auto h-auto max-w-full max-h-full rounded-lg shadow-2xl">
            @endif
        </div>

        <!-- Controls and Pagination (Bottom Center) -->
        <div class="absolute bottom-0 left-0 right-0 z-20 flex items-center justify-center p-4">
            <div class="flex items-center px-4 py-2 space-x-6 rounded-full bg-black/50 backdrop-blur-sm">
                <!-- Previous Button -->
                <button wire:click="navigateImageModal(-1)" @disabled($currentImageModalIndex === 0)
                        class="text-white rounded-full disabled:opacity-30 disabled:cursor-not-allowed hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white">
                    <span class="sr-only">Previous</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Pagination -->
                <p class="text-lg font-medium text-white select-none" aria-live="polite">
                    {{ $currentImageModalIndex + 1 }} / {{ count($imageModalUrls) }}
                </p>

                <!-- Next Button -->
                <button wire:click="navigateImageModal(1)" @disabled($currentImageModalIndex >= count($imageModalUrls) - 1)
                        class="text-white rounded-full disabled:opacity-30 disabled:cursor-not-allowed hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white">
                    <span class="sr-only">Next</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
