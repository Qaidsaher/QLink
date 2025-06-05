<div x-data="{ replyFormVisibleFor: null }">
    @auth
    <form wire:submit.prevent="addComment" class="mb-6">
        <div class="flex items-start space-x-3">
            <img class="flex-shrink-0 object-cover mt-1 rounded-full h-9 w-9" src="{{ Auth::user()->avatarUrl() }}" alt="Your Avatar">
            <div class="flex-1">
                @if($replyToCommentId)
                <div class="mb-1 text-xs text-gray-500 dark:text-gray-400">
                    Replying to <span class="font-semibold">{{ '@'.$replyToUsername }}</span>
                    <button type="button" wire:click="cancelReply" class="ml-1 text-xs text-indigo-500 dark:text-indigo-400 hover:underline">× cancel</button>
                </div>
                @endif
                <textarea wire:model.defer="newCommentContent" id="comment-input-main"
                          class="w-full text-sm placeholder-gray-400 transition duration-150 ease-in-out border-gray-300 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-750 dark:text-gray-200 focus:ring-primary-DEFAULT focus:border-primary-DEFAULT dark:placeholder-gray-500"
                          rows="3"
                          placeholder="{{ $replyToCommentId ? 'Write your reply...' : 'Add a public comment...' }}"
                          required></textarea>
                @error('newCommentContent') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex justify-end mt-2">
            @if($replyToCommentId)
            <button type="button" wire:click="cancelReply" class="px-4 py-2 mr-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-full dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-DEFAULT dark:focus:ring-offset-gray-800">
                Cancel Reply
            </button>
            @endif
            <button type="submit" wire:loading.attr="disabled" wire:target="addComment"
                    class="inline-flex items-center justify-center px-5 py-2 text-xs font-semibold tracking-widest text-white transition duration-150 ease-in-out border border-transparent rounded-full shadow-md bg-primary-DEFAULT hover:bg-primary-dark focus:outline-none focus:border-primary-dark focus:ring focus:ring-primary-light disabled:opacity-50">
                <div wire:loading wire:target="addComment" class="mr-2 btn-spinner" style="width: 0.8em; height: 0.8em; border-left-width: .15em; border-top-width: .15em; border-right-width: .15em; border-bottom-width: .15em;"></div>
                <span>{{ $replyToCommentId ? 'Post Reply' : 'Post Comment' }}</span>
            </button>
        </div>
    </form>
    @else
        <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('login') }}" class="font-semibold text-primary-DEFAULT dark:text-primary-light hover:underline">Log in</a> or
            <a href="{{ route('register') }}" class="font-semibold text-primary-DEFAULT dark:text-primary-light hover:underline">register</a> to comment.
        </p>
    @endauth

    {{-- Comments List --}}
    @if ($comments->isEmpty())
        <p class="py-4 text-sm text-center text-gray-500 dark:text-gray-400">No comments yet. Be the first one!</p>
    @else
        <div class="space-y-5">
            @foreach ($comments as $comment)
                @include('livewire.partials.comment-display', ['comment' => $comment, 'level' => 0, 'postId' => $post->id])
            @endforeach

            @if ($comments->hasMorePages())
                <div class="mt-6 text-center">
                    <button wire:click="nextPage('commentsPage')" wire:loading.attr="disabled" wire:target="nextPage"
                            class="text-sm font-medium text-primary-DEFAULT dark:text-primary-light hover:underline">
                        <span wire:loading wire:target="nextPage">Loading...</span>
                        <span wire:loading.remove wire:target="nextPage">Load More Comments</span>
                    </button>
                </div>
            @endif
            {{-- Or use standard pagination links: --}}
            {{-- <div class="mt-6">{{ $comments->links() }}</div> --}}
        </div>
    @endif
    <script>
        document.addEventListener('livewire:load', function () {
            window.addEventListener('focus-comment-input', event => {
                document.getElementById('comment-input-main')?.focus();
                document.getElementById('comment-input-main')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });
    </script>
</div>
