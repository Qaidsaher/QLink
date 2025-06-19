<div class="p-4 sm:p-6"
     x-data="{ isDragging: false }"
     @dragover.prevent="isDragging = true"
     @dragleave.prevent="isDragging = false"
     @drop.prevent="isDragging = false; if ($event.dataTransfer.files.length > 0) { $refs.attachmentsInput.files = $event.dataTransfer.files; $wire.set('attachments', Array.from($event.dataTransfer.files), true); }">

    <!-- Main Form Container -->
    <div class="relative w-full max-w-2xl mx-auto bg-white border rounded-md border-slate-200 dark:bg-slate-900 dark:border-slate-800">

        <!-- Header -->
        <div class="p-4 border-b border-slate-200 dark:border-slate-800">
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Create a new post</h2>
        </div>

        <form wire:submit.prevent="savePost">
            <!-- Composer Area -->
            <div class="flex gap-4 p-4">
                <!-- User Avatar -->
                <div class="flex-shrink-0">
                    @auth
                                          <img class="object-cover w-12 h-12 rounded-full" src="{{ Auth::user()->avatarUrl() }}" alt="{{ Auth::user()->name }}">


                    @endauth
                    {{-- <img class="object-cover w-12 h-12 rounded-full" src="{{ Auth::user()->avatarUrl() }}" alt="{{ Auth::user()->name }}"> --}}
                </div>

                <!-- Textarea and Previews -->
                <div class="flex-1">
                    <textarea wire:model.lazy="content" id="content" rows="4"
                              class="w-full text-lg bg-transparent border-0 resize-none text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:ring-0"
                              placeholder="What's on your mind, {{ Auth::check()? Auth::user()->name : 'ss' }}?"></textarea>
                    @error('content') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror

                    <!-- =================== ATTACHMENT PREVIEWS =================== -->
                    @if (!empty($attachmentPreviews))
                        <div class="mt-4 space-y-4">
                            <!-- Image Previews Grid -->
                            @if(collect($attachmentPreviews)->where('is_image')->isNotEmpty())
                                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                                    @foreach ($attachmentPreviews as $index => $preview)
                                        @if ($preview['is_image'])
                                            <div wire:key="preview-img-{{ $index }}" class="relative aspect-square">
                                                <img src="{{ $preview['temp_url'] }}" alt="Preview" class="object-cover w-full h-full rounded-xl">
                                                <button type="button" wire:click="removeAttachment({{ $index }})" class="absolute top-1 right-1 p-1.5 bg-black/60 text-white rounded-full hover:bg-black/80 transition-colors">
                                                    <span class="sr-only">Remove file</span>
                                                    <i class="w-4 h-4 fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            <!-- Non-Image Previews List -->
                            @if(collect($attachmentPreviews)->where('is_image', false)->isNotEmpty())
                                <div class="space-y-2">
                                    @foreach ($attachmentPreviews as $index => $preview)
                                        @if (!$preview['is_image'])
                                            <div wire:key="preview-file-{{ $index }}" class="flex items-center justify-between p-2 pl-3 text-sm border rounded-lg border-slate-200 bg-slate-50 dark:bg-slate-800 dark:border-slate-700">
                                                <div class="flex items-center min-w-0 gap-3">
                                                    {{-- File Type Icon with dynamic colors --}}
                                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center
                                                        @switch($preview['file_icon_type'])
                                                            @case('video') bg-purple-100 dark:bg-purple-800/50 text-purple-500 dark:text-purple-400 @break
                                                            @case('audio') bg-teal-100 dark:bg-teal-800/50 text-teal-500 dark:text-teal-400 @break
                                                            @case('pdf') bg-red-100 dark:bg-red-800/50 text-red-500 dark:text-red-400 @break
                                                            @case('document') bg-blue-100 dark:bg-blue-800/50 text-blue-500 dark:text-blue-400 @break
                                                            @case('spreadsheet') bg-green-100 dark:bg-green-800/50 text-green-500 dark:text-green-400 @break
                                                            @case('presentation') bg-yellow-100 dark:bg-yellow-800/50 text-yellow-500 dark:text-yellow-400 @break
                                                            @case('archive') bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 @break
                                                            @default bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400
                                                        @endswitch">
                                                        {{-- Font Awesome Icon based on type --}}
                                                        <i class="fa-lg fa-fw fa-solid
                                                        @switch($preview['file_icon_type'])
                                                            @case('video') fa-file-video @break
                                                            @case('audio') fa-file-audio @break
                                                            @case('pdf') fa-file-pdf @break
                                                            @case('document') fa-file-word @break
                                                            @case('spreadsheet') fa-file-excel @break
                                                            @case('presentation') fa-file-powerpoint @break
                                                            @case('archive') fa-file-zipper @break
                                                            @default fa-file
                                                        @endswitch"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="font-medium truncate text-slate-700 dark:text-slate-200" title="{{ $preview['name'] }}">{{ $preview['name'] }}</p>
                                                        <span class="text-xs text-slate-400">{{ $preview['size'] }}</span>
                                                    </div>
                                                </div>
                                                <button type="button" wire:click="removeAttachment({{ $index }})" class="flex-shrink-0 p-2 ml-2 transition-colors rounded-full text-slate-400 hover:bg-red-100 hover:text-red-500 dark:hover:bg-red-800/50 dark:hover:text-red-400">
                                                    <span class="sr-only">Remove file</span>
                                                    <i class="w-4 h-4 fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                    <!-- ================= END ATTACHMENT PREVIEWS ================= -->
                     @error('attachments') <span class="block mt-2 text-xs text-red-500">{{ $message }}</span> @enderror
                     @foreach ($errors->get('attachments.*') as $message) <span class="block mt-1 text-xs text-red-500">{{ $message[0] }}</span> @endforeach
                </div>
            </div>

            <!-- Action Bar -->
            <div class="flex items-center justify-between p-4 border-t border-slate-200 dark:border-slate-800">
                <!-- Attachment Button -->
                <div class="flex items-center gap-2">
                    <input wire:model="attachments" id="attachmentsInput" x-ref="attachmentsInput" type="file" class="sr-only" multiple accept=".jpg,.jpeg,.png,.gif,.webp,.mp4,.mov,.avi,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.svg">
                    <button type="button" @click="$refs.attachmentsInput.click()" class="p-2 transition-colors rounded-full text-slate-500 hover:bg-blue-100 hover:text-blue-500 dark:hover:bg-slate-800">
                        <i class="w-5 h-5 fa-solid fa-paperclip"></i>
                        <span class="sr-only">Add attachments</span>
                    </button>
                </div>

                <!-- Publish Button -->
                <button type="submit" wire:loading.attr="disabled" wire:target="savePost, attachments"
                        class="inline-flex items-center justify-center px-5 py-2 text-sm font-semibold text-white transition-all duration-200 bg-blue-500 rounded-full shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-slate-900 disabled:opacity-60 disabled:cursor-not-allowed">
                    <div wire:loading wire:target="savePost, attachments" class="mr-2">
                        <i class="w-4 h-4 fas fa-spinner fa-spin"></i>
                    </div>
                    <span wire:loading.remove wire:target="savePost, attachments">Publish</span>
                    <span wire:loading wire:target="savePost, attachments">Publishing...</span>
                </button>
            </div>
        </form>

        <!-- Drag & Drop Overlay -->
        <div x-show="isDragging" x-transition
             class="absolute inset-0 z-10 flex flex-col items-center justify-center text-center border-4 border-blue-500 border-dashed bg-blue-500/20 backdrop-blur-sm rounded-2xl" x-cloak>
             <i class="mb-4 text-5xl text-blue-500 fa-solid fa-upload"></i>
            <p class="text-xl font-bold text-blue-600 dark:text-blue-300">Drop files to upload</p>
        </div>
    </div>

    <!-- Success/Error Alerts -->
    <x-alert-stack />
</div>
