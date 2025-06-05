<form wire:submit.prevent="submit" class="space-y-6">
    {{-- Content Textarea --}}
    <div>
        <label for="content" class="sr-only">Post content</label>
        <div class="flex items-start space-x-3">
            <img class="flex-shrink-0 object-cover w-10 h-10 rounded-full" src="{{ Auth::user()->avatarUrl() }}" alt="Your Avatar">
            <textarea wire:model.lazy="content" id="content" rows="4"
                      class="block w-full placeholder-gray-400 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:ring-primary-DEFAULT focus:border-primary-DEFAULT dark:placeholder-gray-500 sm:text-sm"
                      placeholder="What's on your mind, {{ Auth::user()->name }}?"></textarea>
        </div>
        @error('content') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- File Upload Section --}}
    <div>
        <label for="attachments-input" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Attachments (Max 5 files, 20MB each)
        </label>
        <div class="mt-1">
            <div wire:loading wire:target="attachments" class="text-sm text-gray-500 dark:text-gray-400">
                <i class="mr-1 fas fa-spinner fa-spin"></i> Uploading attachments...
            </div>
            <input id="attachments-input" type="file" wire:model="attachments" multiple
                   class="block w-full text-sm text-gray-500 cursor-pointer dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-DEFAULT/10 file:text-primary-DEFAULT dark:file:bg-primary-dark/20 dark:file:text-primary-light hover:file:bg-primary-DEFAULT/20 dark:hover:file:bg-primary-dark/30">
            @error('attachments') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
            @foreach ($errors->get('attachments.*') as $message) {{-- Display errors for individual files --}}
                <span class="mt-1 text-xs text-red-600 d-block">{{ $message[0] }}</span>
            @endforeach
        </div>
    </div>

    {{-- Attachment Previews --}}
    @if (!empty($attachmentPreviews))
        <div class="mt-4 space-y-3">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Selected Files:</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                @foreach ($attachmentPreviews as $index => $preview)
                    <div class="relative p-2 border border-gray-200 rounded-md group dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex items-center space-x-2">
                            <i class="{{ $preview['icon'] ?? 'fas fa-file' }} text-xl w-6 text-center"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-800 truncate dark:text-gray-200">{{ $preview['name'] }}</p>
                                @if(isset($preview['size']))
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $preview['size'] }}</p>
                                @endif
                            </div>
                            <button type="button" wire:click="removeAttachment({{ $index }})"
                                    class="p-1 text-red-500 rounded-full hover:text-red-700 dark:hover:text-red-400">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </div>
                        @if ($preview['type'] == 'image' && isset($preview['url']))
                            <img src="{{ $preview['url'] }}" alt="Preview {{ $preview['name'] }}" class="object-contain w-full mt-2 rounded-md max-h-32">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Submit Button --}}
    <div class="flex justify-end pt-4">
        <button type="submit" wire:loading.attr="disabled" wire:target="submit,attachments"
                class="inline-flex items-center justify-center px-6 py-2.5 bg-primary-DEFAULT border border-transparent rounded-full font-semibold text-sm text-white tracking-widest hover:bg-primary-dark focus:outline-none focus:border-primary-dark focus:ring focus:ring-primary-light disabled:opacity-50 transition ease-in-out duration-150 shadow-md">
            <div wire:loading wire:target="submit,attachments" class="mr-2 btn-spinner"></div>
            <i wire:loading.remove wire:target="submit,attachments" class="mr-2 fas fa-paper-plane fa-fw"></i>
            Post
        </button>
    </div>

    {{-- For the loading spinner inside button (add to your app.css if you don't have a similar utility) --}}
    <style>
        .btn-spinner,
        .btn-spinner:after {
            border-radius: 50%;
            width: 1.25em;
            height: 1.25em;
        }
        .btn-spinner {
            font-size: 10px;
            position: relative;
            text-indent: -9999em;
            border-top: .2em solid rgba(255, 255, 255, 0.2);
            border-right: .2em solid rgba(255, 255, 255, 0.2);
            border-bottom: .2em solid rgba(255, 255, 255, 0.2);
            border-left: .2em solid #ffffff;
            transform: translateZ(0);
            animation: load8 1.1s infinite linear;
        }
        @keyframes load8 {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</form>
