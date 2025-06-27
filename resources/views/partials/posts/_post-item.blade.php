{{--
    This partial renders a single post item.
    It's designed to be included in loops, like on the main feed or search results page.
    It expects a `$post` object to be passed to it.
--}}
<article wire:key="post-item-{{ $post->id }}" class="flex p-4 space-x-4 border-b border-gray-200 dark:border-slate-700 hover:bg-gray-50/50 dark:hover:bg-slate-800/50">

    {{-- Avatar Column --}}
    <div class="flex-shrink-0">
        <a href="{{ route('profile.show', $post->user->username) }}" wire:navigate>
            <x-avatar :user="$post->user" />
        </a>
    </div>

    {{-- Content Column --}}
    <div class="flex-1">
        {{-- Post Header --}}
        <header class="flex items-center justify-between">
            <div class="flex items-center space-x-1 text-sm leading-none">
                <a href="{{ route('profile.show', $post->user->username) }}" wire:navigate class="font-bold text-gray-900 truncate dark:text-white hover:underline">
                    {{ $post->user->name }}
                </a>
                <span class="text-gray-500 dark:text-gray-400">
                    @<span>{{ $post->user->username }}</span>
                </span>
                <span class="text-gray-500 dark:text-gray-400">&middot;</span>
                <a href="{{ route('posts.show', $post->id) }}" wire:navigate class="text-gray-500 dark:text-gray-400 hover:underline" title="{{ $post->created_at->format('g:i A · M j, Y') }}">
                    {{ $post->created_at->diffForHumans(null, true) }}
                </a>
            </div>

            {{-- More Options Menu --}}
            <div x-data="{ menuOpen: false }" class="relative">
                <button @click="menuOpen = !menuOpen" class="p-1 text-gray-400 rounded-full dark:text-gray-500 hover:bg-blue-100 hover:text-blue-500 dark:hover:bg-slate-700">
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                </button>
                <div x-show="menuOpen" @click.away="menuOpen = false" x-transition class="absolute right-0 z-20 w-48 py-1 mt-2 bg-white border border-gray-200 rounded-md shadow-xl dark:bg-slate-800 dark:border-slate-700" style="display: none;">
                    {{-- Add actions like Report, Mute, etc. here --}}
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">Report Post</a>
                </div>
            </div>
        </header>

        {{-- Post Content Body --}}
        <div class="mt-2 text-base text-gray-800 whitespace-pre-wrap dark:text-gray-200">
            <a href="{{ route('posts.show', $post->id) }}" wire:navigate>
                {!! nl2br(e($post->content)) !!}
            </a>
        </div>

        {{-- Attachments Section --}}
        @if($post->attachments->where('file_type', 'image')->isNotEmpty())
            <div class="mt-3 overflow-hidden border border-gray-200 rounded-2xl dark:border-slate-700">
                <div class="grid gap-0.5 @if($post->attachments->where('file_type', 'image')->count() >= 2) grid-cols-2 @else grid-cols-1 @endif">
                    @foreach($post->attachments->where('file_type', 'image')->take(4) as $attachment)
                        <div class="relative bg-gray-200 dark:bg-slate-800 @if($post->attachments->where('file_type', 'image')->count() === 1) aspect-[16/9] @else aspect-square @endif">
                             <a href="{{ route('posts.show', $post->id) }}" wire:navigate>
                                <img src="{{ $attachment->file_url }}" alt="Post image" class="object-cover w-full h-full">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Action Bar (This would typically be part of a Livewire component to handle state) --}}
        <footer class="flex items-center justify-between max-w-sm mt-4 text-gray-500">
            <div class="flex items-center space-x-2 transition group">
                <div class="p-2 rounded-full group-hover:bg-blue-100 dark:group-hover:bg-blue-800/30"><svg class="w-5 h-5 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z" /></svg></div>
                <span class="text-sm group-hover:text-blue-500">{{ $post->comments_count }}</span>
            </div>
            <div class="flex items-center space-x-2 transition group">
                <div class="p-2 rounded-full group-hover:bg-red-100 dark:group-hover:bg-red-800/30">
                    <svg class="w-5 h-5 @if($post->is_liked) text-red-500 @else group-hover:text-red-500 @endif" fill="@if($post->is_liked) currentColor @else none @endif" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                </div>
                <span class="text-sm @if($post->is_liked) text-red-500 @else group-hover:text-red-500 @endif">{{ $post->likes_count }}</span>
            </div>
            <div class="flex items-center space-x-2 transition group">
                 <div class="p-2 rounded-full group-hover:bg-blue-100 dark:group-hover:bg-blue-800/30"><svg class="w-5 h-5 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8m-4-6l-4-4m0 0L8 6m4-4v12" /></svg></div>
            </div>
        </footer>
    </div>
</article>
