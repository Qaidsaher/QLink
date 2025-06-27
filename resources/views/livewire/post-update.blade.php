<div>
    {{-- Sticky Header for the Edit Page --}}
    <header
        class="sticky top-0 z-20 border-b border-gray-200 bg-white/80 dark:bg-black/80 backdrop-blur-sm dark:border-slate-700">
        <div class="flex items-center justify-between p-2 px-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('posts.show', $post) }}" wire:navigate
                    class="p-2 transition-colors rounded-full hover:bg-gray-200 dark:hover:bg-slate-800">
                    <svg class="w-5 h-5 text-gray-800 dark:text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="sr-only">Cancel</span>
                </a>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Edit Post</h1>
            </div>

        </div>
    </header>

    {{-- Main Form Content --}}
    <div class="p-4">
        <form wire:submit.prevent="updatePost">
            <div class="flex w-full space-x-4">
                <div class="flex-shrink-0">
                    <img class="object-cover w-12 h-12 rounded-full" src="{{ Auth::user()->avatarUrl() }}"
                        alt="{{ Auth::user()->name }}">
                </div>
                <div class="flex-1">
                    {{-- Textarea --}}
                    <textarea wire:model="content" rows="4"
                        class="w-full text-xl bg-transparent border-0 resize-none text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:ring-0"
                        placeholder="What's on your mind?"></textarea>
                    <x-input-error :messages="$errors->get('content')" class="mt-2" />

                    {{-- Attachments Section --}}
                    <div class="mt-4 space-y-4">
                        {{-- Existing Attachments --}}
                        @if(!empty($existingAttachments))
                            <h4 class="text-sm font-bold text-gray-600 dark:text-gray-400">Current Attachments</h4>
                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                                @foreach ($existingAttachments as $attachment)
                                    <div wire:key="existing-{{ $attachment['id'] }}" class="relative aspect-video">
                                        @if($attachment['is_image'])
                                            <img src="{{ $attachment['file_url'] }}" class="object-cover w-full h-full rounded-xl">
                                        @else

                                            <div
                                                class="flex items-center justify-center w-full h-full text-gray-500 bg-gray-100 rounded-xl dark:bg-slate-800">
                                                <i class="text-3xl fa-solid fa-file"></i>
                                            </div>
                                        @endif
                                        <button type="button" wire:click="markAttachmentForDeletion({{ $attachment['id'] }})"
                                            class="absolute top-1.5 right-1.5 p-1 bg-black/60 text-white rounded-full hover:bg-red-600 transition-colors"
                                            title="Remove {{ $attachment['file_name'] }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- New Attachment Previews --}}
                        @if(!empty($newAttachmentPreviews))
                            <h4 class="text-sm font-bold text-gray-600 dark:text-gray-400">New Attachments</h4>
                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                                @foreach ($newAttachmentPreviews as $index => $preview)
                                    <div wire:key="new-preview-{{ $index }}" class="relative aspect-video">
                                        @if($preview['is_image'])
                                            <img src="{{ $preview['temp_url'] }}" alt="Preview"
                                                class="object-cover w-full h-full rounded-xl">
                                        @else

                                            <div
                                                class="flex items-center justify-center w-full h-full text-gray-500 bg-gray-100 rounded-xl dark:bg-slate-800">
                                                <i class="text-3xl fa-solid fa-file"></i>
                                            </div>
                                        @endif
                                        <button type="button" wire:click="removeNewAttachment({{ $index }})"
                                            class="absolute top-1.5 right-1.5 p-1 bg-black/60 text-white rounded-full hover:bg-black/80 transition-colors"
                                            title="Remove {{ $preview['name'] }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <x-input-error :messages="$errors->get('attachments')" class="mt-2" />
                    @foreach ($errors->get('newAttachments.*') as $message) <span
                    class="block mt-1 text-xs text-red-500">{{ $message[0] }}</span> @endforeach
                </div>
            </div>


            {{-- Action Bar --}}
            <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-200 dark:border-slate-700">
                {{-- Left Side: Attachment Icons --}}
                <div class="flex items-center gap-1 text-blue-500">
                    <div class="flex items-center gap-1 text-blue-500">
                        <input wire:model="newAttachments" id="newAttachmentsInput" type="file" class="sr-only"
                            multiple>
                        <label for="newAttachmentsInput"
                            class="p-2 transition-colors rounded-full cursor-pointer hover:bg-blue-100 dark:hover:bg-blue-800/30"
                            title="Add Media">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </label>
                    </div>
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
                <div>
                    <button wire:click="updatePost" wire:loading.attr="disabled"
                        class="px-5 py-2 text-sm font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600 disabled:opacity-70">
                        <span wire:loading.remove wire:target="updatePost">Save</span>
                        <span wire:loading wire:target="updatePost">Saving...</span>
                    </button>
                </div>
        </form>
    </div>
</div>