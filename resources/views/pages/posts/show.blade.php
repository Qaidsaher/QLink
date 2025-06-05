<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{-- You can make this dynamic, e.g., "Post by {{ $post->user->name }}" --}}
            {{ __('Post Details') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto space-y-6 sm:px-6 lg:px-8">
            {{-- Display the Main Post using the PostCard component --}}
            @livewire('post-card', ['post' => $post], key('single-post-'.$post->id))

            {{-- Comments Section --}}
            <div id="comments-section" class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <h3 class="pb-3 mb-4 text-lg font-semibold text-gray-900 border-b border-gray-200 dark:text-gray-100 dark:border-gray-700">
                        Comments ({{ $post->comments_count }})
                    </h3>
                    @livewire('post-comments', ['post' => $post], key('comments-for-post-'.$post->id))
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
