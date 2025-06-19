@php
    $imageAttachments = $post->attachments->where('file_type', 'image');
    // You can add logic for other attachments here too if needed
@endphp

@if($imageAttachments->isNotEmpty())
    <div class="pt-4 mt-4 -mx-5 -mb-6 sm:-mx-6 sm:-mb-6 ">
        <div class="grid gap-px
            @if($imageAttachments->count() === 1) grid-cols-1
            @else grid-cols-2
            @endif">
            @foreach($imageAttachments->take(4) as $index => $attachment)
                <div class="relative bg-gray-200 dark:bg-gray-700 @if($imageAttachments->count() === 1) aspect-w-16 aspect-h-9 @else aspect-square @endif">
                    <img src="{{ $attachment->file_url }}" alt="Post image"
                        wire:click="openImageModal({{ $index }})"
                        class="object-cover w-full h-full transition-opacity duration-150 rounded-md cursor-pointer hover:opacity-90">
                    @if($imageAttachments->count() > 4 && $loop->last)
                        <div wire:click="openImageModal(3)" class="absolute inset-0 flex items-center justify-center text-2xl font-bold text-white bg-black cursor-pointer bg-opacity-60 hover:bg-opacity-70">
                            +{{ $imageAttachments->count() - 4 }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
