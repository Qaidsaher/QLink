<div class="flex items-start space-x-3 {{ $level > 0 ? 'ml-'.($level > 2 ? 12 : $level * 6) : '' }}"> {{-- Indent replies, max indent at level 2 --}}
    <a href="{{ route('profile.show', $comment->user->username) }}">
        <img src="{{ $comment->user->avatarUrl() }}" alt="{{ $comment->user->name }}" class="flex-shrink-0 object-cover w-8 h-8 rounded-full">
    </a>
    <div class="flex-1 p-3 shadow-sm bg-gray-50 dark:bg-gray-750 rounded-xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-1.5">
                <a href="{{ route('profile.show', $comment->user->username) }}" class="text-xs font-semibold text-gray-800 dark:text-gray-100 hover:underline">{{ $comment->user->name }}</a>
                <span class="text-xs text-gray-400 dark:text-gray-500">·</span>
                <span class="text-xs text-gray-400 dark:text-gray-500" title="{{ $comment->created_at->toDayDateTimeString() }}">{{ $comment->created_at->diffForHumans(null, true, true) }}</span> {{-- Short diff --}}
            </div>
            {{-- More options for comment (edit, delete) can go here --}}
        </div>
        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1.5 leading-relaxed">
            {!! nl2br(e($comment->content)) !!}
        </p>
        <div class="flex items-center mt-2 space-x-3">
            <button wire:click="$emitTo('post-comments', 'setReplyTo', {{ $comment->id }})" class="text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-primary-DEFAULT dark:hover:text-primary-light">
                <i class="fas fa-reply fa-fw"></i> Reply
            </button>
            {{-- TODO: Implement Like for comments --}}
            {{-- <button class="text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-red-500">
                <i class="far fa-heart fa-fw"></i> 0
            </button> --}}
        </div>
    </div>
</div>

{{-- Display Replies Recursively --}}
@if ($comment->replies->isNotEmpty())
    <div class="mt-3 space-y-3">
        @foreach ($comment->replies as $reply)
            @include('livewire.partials.comment-display', ['comment' => $reply, 'level' => $level + 1, 'postId' => $postId])
        @endforeach
         {{-- Link to load more replies for THIS comment if applicable --}}
        @if ($comment->replies_count > $comment->replies->count())
            <div class="{{ $level > 0 ? 'ml-'.($level > 2 ? 12 : $level * 6) : '' }} pl-11"> {{-- Align with parent avatar --}}
                <button wire:click="$emitTo('post-comments', 'loadMoreReplies', {{ $comment->id }})" class="text-xs text-primary-DEFAULT dark:text-primary-light hover:underline">
                    View more replies ({{ $comment->replies_count - $comment->replies->count() }})
                </button>
            </div>
        @endif
    </div>
@endif
