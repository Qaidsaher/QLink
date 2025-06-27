<div>
   
    <div class="container min-h-screen p-0 mx-auto md:p-0" >
        <div x-data="{
            init() {
           const bottomTrigger = this.$refs.bottomTrigger;

          // Ensure the trigger element exists before observing
          if (!bottomTrigger) {
              return;
          }

          const observer = new IntersectionObserver((entries) => {
              entries.forEach(entry => {
                  // Check if the element is intersecting
                  if (entry.isIntersecting) {
                      // Dispatch the event to the Livewire component
                       @this.dispatch('scrollBottom');
                  }
               });
             }, {
               root: null, // Observe relative to the viewport
               threshold: 0.1 // Fire when 10% of the element is visible
           });

            observer.observe(bottomTrigger);
           }
            }" class="max-w-2xl mx-auto bg-white border-r border-gray-200 dark:bg-black dark:border-slate-700">
            {{-- Post Loop --}}
            @if ($posts && $posts->isNotEmpty())
                @foreach ($posts as $post)

                    <article wire:key="post-{{ $post->id }}"
                        class="flex p-4 space-x-4 border-b border-gray-200 dark:border-slate-700">
                        {{-- Avatar Column --}}
                        <div class="flex-shrink-0">
                            <a href="{{ route('profile.show', ['username' => $post->user->username, 'user' => $post->user]) }}"
                                wire:navigate>
                                <x-avatar :user="$post->user" />
                            </a>
                        </div>

                        {{-- Content Column --}}
                        <div class="flex-1">
                            {{-- Post Header --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-start space-x-1 text-sm">
                                    <a href="{{ route('profile.show', ['username' => $post->user->username, 'user' => $post->user]) }}"
                                        class="font-bold text-gray-900 truncate dark:text-white hover:underline" wire:navigate>
                                        <div class="flex flex-col">
                                            <span> {{ $post->user->name }}</span>
                                            <span class="text-gray-500 dark:text-gray-400">
                                                @<span>
                                                    {{ $post->user->username }}
                                                </span>
                                            </span>
                                        </div>

                                    </a>

                                    <span class="text-gray-500 dark:text-gray-400">&middot;</span>
                                    <a href="{{ route('posts.show', $post->id) }}" wire:navigate
                                        class="text-gray-500 dark:text-gray-400 hover:underline">
                                        {{ $post->created_at->diffForHumans(null, true) }}
                                    </a>
                                </div>


                                <!-- Dropdown menu -->
                                <div x-data="{ menuOpen: false, showDeleteModal: false }" class="relative">
                                    <button @click="menuOpen = !menuOpen" class="text-gray-500 hover:text-slate-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>

                                    <div x-show="menuOpen" @click.away="menuOpen = false" x-transition
                                        class="absolute right-0 z-20 w-48 py-1 mt-2 bg-white border border-gray-200 rounded-md shadow-xl dark:bg-slate-800 dark:border-slate-700"
                                        style="display: none;">
                                        <a href="#"
                                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">
                                            Report Post
                                        </a>


                                        @if(auth()->id() === $post->user_id)
                                            <a href="{{ route('posts.edit', $post) }}" wire:navigate
                                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">
                                                <svg class="inline w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.232 5.232l3.536 3.536M9 13l6.536-6.536a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L5 11.828a2 2 0 010-2.828L9 13z" />
                                                </svg>
                                                Edit Post
                                            </a>
                                            <button @click.prevent="showDeleteModal = true; menuOpen = false"
                                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 dark:text-red-400">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Delete Post
                                            </button>
                                        @endif


                                    </div>

                                    <!-- Modal -->
                                    <div x-show="showDeleteModal" x-transition x-cloak
                                        class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50">
                                        <div
                                            class="w-full max-w-md p-6 mx-auto bg-white shadow-lg dark:bg-slate-800 rounded-xl">
                                            <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-100">
                                                Are you sure you want to delete this post?
                                            </h2>
                                            <p class="mb-6 text-sm text-gray-600 dark:text-gray-300">
                                                This action cannot be undone.
                                            </p>

                                            <div class="flex justify-end space-x-2">
                                                <button @click="showDeleteModal = false"
                                                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md dark:text-gray-300 dark:bg-slate-700 hover:bg-gray-200">
                                                    Cancel
                                                </button>

                                                <button
                                                    @click="showDeleteModal = false; Livewire.dispatch('deletePost', { postId: {{ $post->id }} });"
                                                    class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700">
                                                    Delete
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- Post Content --}}
                            <div class="mt-1 text-base text-gray-800 dark:text-gray-200">
                                {!! nl2br(e($post->content)) !!}
                            </div>
                            <div class="p-3 mt-2">
                                @include('partials.posts._post-attachments')
                            </div>

                            {{-- @livewire('post-action', ['post' => $post],  wire:key="post-action-{{ $post->id }}") --}}

                            <livewire:post-action :post="$post" wire:key="post-card-{{ $post->id }}" />
                        </div>
                    </article>
                @endforeach
                <div class="p-2 space-y-4 animate-pulse">

                    {{-- Dummy div to watch when it enters the viewport --}}
                    <div x-ref="bottomTrigger" class="h-0">
                    </div>
                    @for ($i = 0; $i < 3; $i++)
                        <div class="flex p-2 space-x-4 border-b border-gray-200 dark:border-slate-700">
                            <!-- Avatar -->
                            <div class="w-12 h-12 rounded-full bg-slate-300 dark:bg-slate-600"></div>

                            <!-- Content -->
                            <div class="flex-1 py-1 space-y-3">
                                <!-- Title line -->
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-2 text-sm">
                                        <div class="flex flex-col space-y-1">
                                            <div class="w-24 h-4 bg-gray-300 rounded dark:bg-slate-600"></div>
                                            <div class="w-20 h-3 bg-gray-200 rounded dark:bg-slate-700"></div>
                                        </div>
                                        <div class="w-4 h-3 bg-gray-300 rounded dark:bg-slate-600"></div>
                                        <div class="w-16 h-3 bg-gray-300 rounded dark:bg-slate-600"></div>
                                    </div>
                                    <div class="flex flex-col items-center justify-center space-y-1">
                                        <div class="w-1 h-1 bg-gray-300 rounded-full dark:bg-slate-600"></div>
                                        <div class="w-1 h-1 bg-gray-300 rounded-full dark:bg-slate-600"></div>
                                        <div class="w-1 h-1 bg-gray-300 rounded-full dark:bg-slate-600"></div>
                                    </div>

                                </div> <!-- Text lines -->
                                {{-- Post Content --}}
                                <div class="mt-2 space-y-2">
                                    <div class="w-11/12 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>
                                    <div class="w-9/12 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>
                                    <div class="w-6/12 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>
                                </div>

                                {{-- Action Bar --}}

                                <div class="flex pt-2 space-x-4">
                                    <div class="w-16 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>
                                    <div class="w-16 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>
                                    <div class="w-16 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>
                                    <div class="w-16 h-4 bg-gray-200 rounded dark:bg-slate-700"></div>

                                </div>
                            </div>
                        </div>
                    @endfor


                </div>

            @else
                <div wire:init="loadPosts" class="p-10 text-center">
                    <p class="text-gray-500 dark:text-gray-400">Loading posts...</p>
                </div>
            @endif
        </div>
    </div>
    @include('partials.posts._image-modal')
</div>