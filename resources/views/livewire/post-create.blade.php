<div class="relative" x-data="{ isDragging: false }" @dragover.prevent="isDragging = true"
    @dragleave.prevent="isDragging = false"
    @drop.prevent="isDragging = false; if ($event.dataTransfer.files.length > 0) { $refs.attachmentsInput.files = $event.dataTransfer.files; $wire.set('attachments', Array.from($event.dataTransfer.files), true); }">

    @if ($isPage)
        {{-- Sticky Header --}}
        <header
            class="sticky top-0 z-30 transition-colors duration-300 border-b bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border-slate-200/80 dark:border-slate-800/80">
            <div class="flex items-center gap-2 px-2 py-3 ">
                <a href="{{ url()->previous(route('feed')) }}" wire:navigate
                    class="p-2 transition-all duration-200 rounded-full text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Create a new post
                </h1>
            </div>
        </header>
    @endif




    {{-- The component is now part of the feed, separated by a bottom border --}}
    <div class="flex p-4 space-x-4   @if($isPage)
        border-b
    @else
        border-r
    @endif border-gray-200 dark:border-slate-700">

        {{-- User Avatar --}}
        <div class="flex-shrink-0">
            @auth
                <img class="object-cover w-12 h-12 rounded-full" src="{{ Auth::user()->avatarUrl() }}"
                    alt="{{ Auth::user()->name }}">
            @endauth
        </div>

        {{-- Form and Content Area --}}
        <div class="flex-1">
            <form wire:submit.prevent="savePost" class="flex flex-col">

                {{-- Textarea for Post Content --}}
                <textarea wire:model.lazy="content" id="content" rows="3"
                    class="w-full text-xl bg-transparent border-0 resize-none text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:ring-0"
                    placeholder="What is happening?!"></textarea>

                @error('content') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror

                {{-- Attachment Previews --}}
                @if (!empty($attachmentPreviews))
                    <div class="mt-4 space-y-4">
                        {{-- Image Previews Grid --}}
                        @if(collect($attachmentPreviews)->where('is_image')->isNotEmpty())
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($attachmentPreviews as $index => $preview)
                                    @if ($preview['is_image'])
                                        <div wire:key="preview-img-{{ $index }}" class="relative aspect-video">
                                            <img src="{{ $preview['temp_url'] }}" alt="Preview"
                                                class="object-cover w-full h-full rounded-xl">
                                            <button type="button" wire:click="removeAttachment({{ $index }})"
                                                class="absolute top-1.5 right-1.5 p-1 bg-black/60 text-white rounded-full hover:bg-black/80 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        {{-- Non-Image Previews List --}}
                        @if(collect($attachmentPreviews)->where('is_image', false)->isNotEmpty())
                            <div class="space-y-2">
                                @foreach ($attachmentPreviews as $index => $preview)
                                    @if (!$preview['is_image'])
                                        <div wire:key="preview-file-{{ $index }}"
                                            class="flex items-center justify-between p-2 pl-3 text-sm border rounded-lg border-slate-200 bg-slate-50 dark:bg-slate-800 dark:border-slate-700">
                                            <div class="flex items-center min-w-0 gap-3">
                                                <i class="fa-lg fa-fw fa-solid fa-file text-slate-500"></i>
                                                <div class="min-w-0">
                                                    <p class="font-medium truncate text-slate-700 dark:text-slate-200"
                                                        title="{{ $preview['name'] }}">{{ $preview['name'] }}</p>
                                                    <span class="text-xs text-slate-400">{{ $preview['size'] }}</span>
                                                </div>
                                            </div>
                                            <button type="button" wire:click="removeAttachment({{ $index }})"
                                                class="flex-shrink-0 p-2 ml-2 transition-colors rounded-full text-slate-400 hover:bg-red-100 hover:text-red-500 dark:hover:bg-red-800/50 dark:hover:text-red-400">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Display validation errors for attachments --}}
                @error('attachments') <span class="block mt-2 text-xs text-red-500">{{ $message }}</span> @enderror
                @foreach ($errors->get('attachments.*') as $message) <span
                class="block mt-1 text-xs text-red-500">{{ $message[0] }}</span> @endforeach

                {{-- Action Bar --}}
                <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-200 dark:border-slate-700">
                    {{-- Left Side: Attachment Icons --}}
                    <div class="flex items-center gap-1 text-blue-500">
                        <input wire:model="attachments" id="attachmentsInput" x-ref="attachmentsInput" type="file"
                            class="sr-only" multiple
                            accept=".jpg,.jpeg,.png,.gif,.webp,.mp4,.mov,.avi,.pdf,.doc,.docx,.xls,.xlsx,.zip,.svg">
                        <button type="button" @click="$refs.attachmentsInput.click()"
                            class="p-2 transition-colors rounded-full hover:bg-blue-100 dark:hover:bg-blue-800/30"
                            title="Media">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </button>
                        {{-- Placeholder icons for UI matching --}}
                        <button type="button"
                            class="p-2 transition-colors rounded-full hover:bg-blue-100 dark:hover:bg-blue-800/30"
                            title="GIF"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg></button>
                        <button type="button"
                            class="p-2 transition-colors rounded-full hover:bg-blue-100 dark:hover:bg-blue-800/30"
                            title="Poll"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg></button>
                    </div>

                    {{-- Right Side: Publish Button --}}
                    <button type="submit" wire:loading.attr="disabled" wire:target="savePost, attachments"
                        class="inline-flex items-center justify-center px-5 py-2 text-sm font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600 focus:outline-none disabled:opacity-70 disabled:cursor-not-allowed">
                        <div wire:loading wire:target="savePost, attachments" class="mr-2">
                            <svg class="w-4 h-4 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <span wire:loading.remove wire:target="savePost, attachments">Post</span>
                        <span wire:loading wire:target="savePost, attachments">Posting...</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Drag & Drop Overlay --}}
    <div x-show="isDragging" x-transition
        class="absolute inset-0 z-10 flex flex-col items-center justify-center text-center border-2 border-blue-500 border-dashed bg-blue-500/10 backdrop-blur-sm rounded-2xl"
        x-cloak>
        <svg class="w-16 h-16 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>
        <p class="mt-4 text-xl font-bold text-blue-600 dark:text-blue-300">Drop your files here</p>
    </div>
</div>
 
{{-- <script>
document.addEventListener('livewire:init', () => {
  Livewire.on('postCreated', (event) => {
    const id = event.postId;
    // alert('/📬 postCreated received for ID ' + id);

    // wait 500ms before refreshing
    setTimeout(() => {
    //   alert('🔄 Now refreshing posts after delay');
    window.EchoPresenceManager.refresh();
    }, 1000);
  });
});
</script> --}}

