@php
    $imageAttachments = $post->attachments->where('file_type', 'image');
    $otherAttachments = $post->attachments->whereNotIn('file_type', ['image']);
@endphp

@if($imageAttachments->isNotEmpty())
    <div class="mt-3 overflow-hidden border border-gray-200 rounded-2xl dark:border-slate-700">
        <div
            class="grid gap-0.5
                                                                                            @if($imageAttachments->count() === 1) grid-cols-1
                                                                                            @elseif($imageAttachments->count() >= 2) grid-cols-2 @endif">
            @foreach($imageAttachments->take(4) as $index => $attachment)
                <div
                    class="relative bg-gray-200 dark:bg-slate-800 @if($imageAttachments->count() === 1) aspect-[16/9] @else aspect-square @endif">
                    <img src="{{ $attachment->file_url }}" alt="Post image"
                        wire:click="openImageModal({{ $post->id }}, {{ $index }})"
                        class="object-cover w-full h-full cursor-pointer">
                    @if($imageAttachments->count() > 4 && $loop->iteration === 4)
                        <div wire:click="openImageModal({{ $post->id }}, 3)"
                            class="absolute inset-0 flex items-center justify-center text-2xl font-bold text-white bg-black cursor-pointer bg-opacity-60">
                            +{{ $imageAttachments->count() - 3 }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Other Attachments (Styled minimally) --}}
@if($otherAttachments->isNotEmpty())
    <div class="mt-3 space-y-2">
        @foreach($otherAttachments as $attachment)
            <a href="{{ $attachment->file_url }}" target="_blank"
                class="block p-3 transition bg-gray-100 border border-gray-200 rounded-lg dark:bg-slate-800 dark:border-slate-700 hover:bg-gray-200 dark:hover:bg-slate-700">
                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                    {{-- Generic Link Icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <div>
                        <p class="font-semibold">{{ $attachment->fileName() }}</p>
                        <p class="text-xs text-blue-500 dark:text-blue-400">
                            @if($attachment->file_type === 'video') Watch video @else Download File @endif
                        </p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endif