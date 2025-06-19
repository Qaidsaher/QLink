<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Account Settings & Profile Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- This is our new, modern tab system --}}
            <x-tab.container wire:model.live="activeTab">

                {{-- Tab Navigation Bar --}}
                <x-tab.list>
                    <x-tab.item name="edit_profile">
                        <i class="w-5 h-5 mr-2 fas fa-user-edit"></i>
                        Profile
                    </x-tab.item>

                    <x-tab.item name="my_posts">
                        <i class="w-5 h-5 mr-2 fas fa-file-alt"></i>
                        Posts
                        <span
                            class="ml-2 py-0.5 px-2 rounded-full text-xs font-medium bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            {{ $user->posts_count }}
                        </span>
                    </x-tab.item>

                    <x-tab.item name="my_followers">
                        <i class="w-5 h-5 mr-2 fas fa-user-friends"></i>
                        Followers
                        <span
                            class="ml-2 py-0.5 px-2 rounded-full text-xs font-medium bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            {{ $user->followers_count }}
                        </span>
                    </x-tab.item>

                    <x-tab.item name="my_following">
                        <i class="w-5 h-5 mr-2 fas fa-user-plus"></i>
                        Following
                        <span
                            class="ml-2 py-0.5 px-2 rounded-full text-xs font-medium bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            {{ $user->following_count }}
                        </span>
                    </x-tab.item>

                    <x-tab.item name="notifications_settings">
                        <i class="w-5 h-5 mr-2 fas fa-bell"></i>
                        Notifications
                    </x-tab.item>

                    <x-tab.item name="account_settings">
                        <i class="w-5 h-5 mr-2 fas fa-cog"></i>
                        Account
                    </x-tab.item>
                </x-tab.list>


                {{-- Tab Content Panels --}}
                <div class="mt-2">
                    <x-tab.panel name="edit_profile">
                        {{-- Profile Information Section (from previous edit-form.blade.php) --}}
                        <section class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                            <header
                                class="px-6 py-4 border-b border-gray-200 bg-gray-50 dark:bg-gray-700/50 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Information</h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update your account's profile
                                    information.</p>
                            </header>
                            <form wire:submit.prevent="saveProfileInformation" class="p-6 space-y-6">
                                {{-- Cover Photo Input --}}
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Cover
                                        Photo</label>
                                    <div
                                        class="mt-1 group relative aspect-[16/7] sm:aspect-[3/1] bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center overflow-hidden border border-dashed border-gray-300 dark:border-gray-600">
                                        @php $currentCoverUrl = $user->cover_photo_path ? Storage::url($user->cover_photo_path) : null; @endphp
                                        @if ($new_cover_photo) <img src="{{ $new_cover_photo->temporaryUrl() }}"
                                            alt="New Cover Preview" class="absolute inset-0 object-cover w-full h-full">
                                        @elseif ($currentCoverUrl) <img src="{{ $currentCoverUrl }}" alt="Current Cover"
                                            class="absolute inset-0 object-cover w-full h-full">
                                        @else <div class="text-slate-400 dark:text-slate-500"><svg
                                                xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg><span class="block mt-1 text-xs">Upload cover</span></div>
                                        @endif
                                        <label for="new_cover_photo_input"
                                            class="absolute inset-0 flex items-center justify-center transition-all duration-300 bg-black bg-opacity-0 opacity-0 cursor-pointer group-hover:bg-opacity-50 group-hover:opacity-100">
                                            <div
                                                class="p-2 text-center text-white bg-black rounded-lg bg-opacity-30 group-hover:bg-opacity-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg><span class="block mt-1 text-xs">Change</span>
                                            </div>
                                        </label>
                                        <input type="file" wire:model="new_cover_photo" id="new_cover_photo_input"
                                            class="hidden" accept="image/*">
                                    </div>
                                    @if ($user->cover_photo_path) <button type="button" wire:click="removeCoverPhoto"
                                        class="mt-2 text-xs text-red-600 dark:text-red-400 hover:underline">Remove
                                    Cover</button> @endif
                                    @error('new_cover_photo') <span
                                        class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- Avatar Input --}}
                                <div>
                                    <label
                                        class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Profile
                                        Photo</label>
                                    <div class="flex items-center mt-1 space-x-4">
                                        <span
                                            class="relative inline-block w-20 h-20 overflow-hidden bg-gray-100 border-2 border-gray-200 rounded-full dark:bg-gray-700 group dark:border-gray-600">
                                            @if ($new_avatar) <img src="{{ $new_avatar->temporaryUrl() }}"
                                                alt="New Avatar Preview" class="object-cover w-full h-full">
                                            @else <img src="{{Storage::url($user->avatar) }}" alt="Current Avatar"
                                                class="object-cover w-full h-full">
                                            @endif
                                            <label for="new_avatar_input"
                                                class="absolute inset-0 flex items-center justify-center transition-all duration-300 bg-black bg-opacity-0 opacity-0 cursor-pointer group-hover:bg-opacity-50 group-hover:opacity-100">
                                                <div
                                                    class="text-center text-white p-1.5 rounded-lg bg-black bg-opacity-30 group-hover:bg-opacity-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
                                            </label>
                                            <input type="file" wire:model="new_avatar" id="new_avatar_input"
                                                class="hidden" accept="image/*">
                                        </span>
                                        <div>
                                            <label for="new_avatar_input_btn_alt"
                                                class="cursor-pointer py-1.5 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">Change</label>
                                            <input type="file" wire:model="new_avatar" id="new_avatar_input_btn_alt"
                                                class="hidden" accept="image/*">
                                            @if ($user->avatar && $user->avatar_url !== App\Models\User::defaultAvatarUrlPlaceholder())
                                                <button type="button" wire:click="removeAvatar"
                                                    class="ml-2 text-xs text-red-600 dark:text-red-400 hover:underline">Remove</button>
                                            @endif
                                        </div>
                                    </div>
                                    @error('new_avatar') <span
                                        class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- Name, Username, Email, Bio, Location, Website inputs (same as before) --}}
                                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                    <div class="sm:col-span-3"><label for="name"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label><input
                                            type="text" wire:model.defer="name" id="name"
                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 sm:text-sm">@error('name')
                                            <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="sm:col-span-3"><label for="username"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                                        <div class="flex mt-1 rounded-md shadow-sm"><span
                                                class="inline-flex items-center px-3 text-gray-500 border border-r-0 border-gray-300 rounded-l-md bg-gray-50 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 sm:text-sm">@</span><input
                                                type="text" wire:model.defer="username" id="username"
                                                class="flex-1 block w-full min-w-0 border-gray-300 rounded-none rounded-r-md focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 sm:text-sm">
                                        </div>@error('username') <span
                                        class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="sm:col-span-6"><label for="email"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label><input
                                            type="email" wire:model.defer="email" id="email"
                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 sm:text-sm">@error('email')
                                            <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="sm:col-span-6"><label for="bio"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bio</label><textarea
                                            wire:model.defer="bio" id="bio" rows="3"
                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 sm:text-sm"></textarea>@error('bio')
                                            <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="sm:col-span-3"><label for="location"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label><input
                                            type="text" wire:model.defer="location" id="location"
                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 sm:text-sm">@error('location')
                                            <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="sm:col-span-3"><label for="website"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label><input
                                            type="url" wire:model.defer="website" id="website"
                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 sm:text-sm"
                                            placeholder="https://">@error('website') <span
                                            class="text-xs text-red-500">{{ $message }}</span> @enderror</div>
                                </div>
                                <div class="flex justify-end pt-5">
                                    <button type="submit" wire:loading.attr="disabled"
                                        wire:target="saveProfileInformation, new_avatar, new_cover_photo"
                                        class="inline-flex justify-center px-4 py-2 ml-3 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-indigo-500 disabled:opacity-50">
                                        <span wire:loading
                                            wire:target="saveProfileInformation, new_avatar, new_cover_photo"
                                            class="w-4 h-4 mr-2 -ml-1 animate-spin"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg></span>
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </section>
                    </x-tab.panel>

                    <x-tab.panel name="my_posts">
                        <section>
                            <header class="mb-4">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">My Posts</h3>
                            </header>
                            @if($myPostsList->isNotEmpty())
                                <div class="space-y-6">
                                    @foreach ($myPostsList as $post)
                                        {{-- Reuse simplified post item structure from UserProfile blade --}}
                                        <article wire:key="my-post-{{ $post->id }}"
                                            class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700/50">
                                            {{-- ... Post content, attachments, actions (like delete for own posts) ... --}}
                                            <div class="p-4">
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $post->created_at->format('M d, Y \a\t H:i') }}
                                                </p>
                                                <div
                                                    class="mt-1 text-sm prose text-gray-700 dark:text-gray-300 dark:prose-invert max-w-none">
                                                    {!! nl2br(e(Str::limit($post->content, 150))) !!}
                                                </div>
                                                {{-- Link to view full post or edit post --}}
                                            </div>
                                        </article>
                                    @endforeach
                                    @if ($myPostsList->hasPages())
                                        <div class="py-4">{{ $myPostsList->links() }}</div>
                                    @endif
                                </div>
                            @else
                                <p class="py-8 text-center text-gray-500 dark:text-gray-400">You haven't created any posts
                                    yet.</p>
                            @endif
                        </section>
                    </x-tab.panel>

                    <x-tab.panel name="my_followers">
                        <section>
                            <header class="mb-4">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Who's Following Me</h3>
                            </header>
                            {{-- Followers list (similar structure to UserProfile blade) --}}
                            @if($myFollowersList->isNotEmpty())
                                <ul role="list"
                                    class="bg-white divide-y divide-gray-200 shadow dark:divide-gray-700 dark:bg-gray-800 sm:rounded-md">
                                    @foreach($myFollowersList as $follower)
                                        <li
                                            class="px-4 py-4 transition-colors sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <a href="{{ route('profile.show', ['user' => $follower->username]) }}">
                                                        <img class="object-cover w-10 h-10 rounded-full"
                                                            src="{{ $follower->avatar_url }}" alt="{{ $follower->name }}">
                                                    </a>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                        <a href="{{ route('profile.show', ['user' => $follower->username]) }}"
                                                            class="hover:underline">{{ $follower->name }}</a>
                                                    </p>
                                                    <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                                                        @<span>{{ $follower->username }}</span></p>
                                                </div>
                                                <div>
                                                    {{-- Follow button for the follower (if you want to follow back) --}}
                                                    @livewire('follow-button', ['userIdToFollow' => $follower->id, 'isSmall' => true], key('follower-list-follow-btn-' . $follower->id))
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                @if ($myFollowersList->hasPages())
                                    <div class="px-4 py-4 bg-white dark:bg-gray-800 sm:px-6">{{ $myFollowersList->links() }}
                                </div> @endif
                            @else
                                <p class="py-8 text-center text-gray-500 dark:text-gray-400">You don't have any followers
                                    yet.</p>
                            @endif
                        </section>
                    </x-tab.panel>

                    <x-tab.panel name="my_following">
                        <section>
                            <header class="mb-4">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Who I'm Following</h3>
                            </header>
                            {{-- Following list (similar structure) --}}
                            @if($myFollowingList->isNotEmpty())
                                <ul role="list"
                                    class="bg-white divide-y divide-gray-200 shadow dark:divide-gray-700 dark:bg-gray-800 sm:rounded-md">
                                    @foreach($myFollowingList as $followedUser)
                                        <li
                                            class="px-4 py-4 transition-colors sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <a href="{{ route('profile.show', ['user' => $followedUser->username]) }}">
                                                        <img class="object-cover w-10 h-10 rounded-full"
                                                            src="{{ $followedUser->avatar_url }}"
                                                            alt="{{ $followedUser->name }}">
                                                    </a>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                        <a href="{{ route('profile.show', ['user' => $followedUser->username]) }}"
                                                            class="hover:underline">{{ $followedUser->name }}</a>
                                                    </p>
                                                    <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                                                        @<span>{{ $followedUser->username }}</span></p>
                                                </div>
                                                <div>
                                                    {{-- This FollowButton will show "Following" as Auth::user is following them
                                                    --}}
                                                    @livewire('follow-button', ['userIdToFollow' => $followedUser->id, 'isSmall' => true], key('following-list-follow-btn-' . $followedUser->id))
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                @if ($myFollowingList->hasPages())
                                    <div class="px-4 py-4 bg-white dark:bg-gray-800 sm:px-6">{{ $myFollowingList->links() }}
                                </div> @endif
                            @else
                                <p class="py-8 text-center text-gray-500 dark:text-gray-400">You are not following anyone
                                    yet.</p>
                            @endif
                        </section>

                    </x-tab.panel>

                    <x-tab.panel name="notifications_settings">
                        <section class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                            <header
                                class="px-6 py-4 border-b border-gray-200 bg-gray-50 dark:bg-gray-700/50 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notification Preferences
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage how you receive
                                    notifications.</p>
                            </header>
                            <form wire:submit.prevent="saveNotificationSettings" class="p-6 space-y-6">
                                {{-- Example Notification Toggle --}}
                                <div class="flex items-center justify-between py-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">New Follower Emails
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Receive an email when
                                            someone new follows you.</p>
                                    </div>
                                    <button type="button" wire:click="$toggle('notifyOnNewFollower')"
                                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-indigo-500 {{ $notifyOnNewFollower ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-600' }}"
                                        role="switch" :aria-checked="$notifyOnNewFollower ? 'true' : 'false'">
                                        <span class="sr-only">New Follower Emails</span>
                                        <span aria-hidden="true"
                                            class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $notifyOnNewFollower ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                    </button>
                                </div>
                                {{-- Add more toggles for other notification types --}}
                                <div class="flex items-center justify-between py-3 border-t dark:border-gray-700">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Post Like
                                            Notifications</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Notify me when someone likes
                                            my post.</p>
                                    </div>
                                    <button type="button" wire:click="$toggle('notifyOnPostLike')"
                                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {{ $notifyOnPostLike ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-600' }}"
                                        role="switch" :aria-checked="$notifyOnPostLike ? 'true' : 'false'"><span
                                            class="sr-only">Post Like Notifications</span><span aria-hidden="true"
                                            class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $notifyOnPostLike ? 'translate-x-5' : 'translate-x-0' }}"></span></button>
                                </div>
                                <div class="flex items-center justify-between py-3 border-t dark:border-gray-700">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">New Comment
                                            Notifications</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Notify me when someone
                                            comments on my post.</p>
                                    </div>
                                    <button type="button" wire:click="$toggle('notifyOnComment')"
                                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {{ $notifyOnComment ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-600' }}"
                                        role="switch" :aria-checked="$notifyOnComment ? 'true' : 'false'"><span
                                            class="sr-only">New Comment Notifications</span><span aria-hidden="true"
                                            class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $notifyOnComment ? 'translate-x-5' : 'translate-x-0' }}"></span></button>
                                </div>

                                <div class="flex justify-end pt-5">
                                    <button type="submit" wire:loading.attr="disabled"
                                        wire:target="saveNotificationSettings"
                                        class="inline-flex justify-center px-4 py-2 ml-3 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-indigo-500 disabled:opacity-50">
                                        Save Notification Settings
                                    </button>
                                </div>
                            </form>
                        </section>

                    </x-tab.panel>

                    <x-tab.panel name="account_settings">
                        {{-- Update Password Section (from previous edit-form.blade.php) --}}
                        <section class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                            <header
                                class="px-6 py-4 border-b border-gray-200 bg-gray-50 dark:bg-gray-700/50 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Update Password</h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Ensure your account is using a
                                    long, random password to stay secure.</p>
                            </header>
                            <form wire:submit.prevent="updatePassword" class="p-6 space-y-6">
                                {{-- Password fields (current, new, confirm) --}}
                                <div><label for="current_password"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current
                                        Password</label><input type="password" wire:model.defer="current_password"
                                        id="current_password"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 sm:text-sm">@error('current_password')
                                        <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div><label for="new_password"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">New
                                        Password</label><input type="password" wire:model.defer="new_password"
                                        id="new_password"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 sm:text-sm">@error('new_password')
                                        <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div><label for="new_password_confirmation"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New
                                        Password</label><input type="password"
                                        wire:model.defer="new_password_confirmation" id="new_password_confirmation"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 sm:text-sm">
                                </div>
                                <div class="flex items-center justify-end">
                                    <button type="submit" wire:loading.attr="disabled" wire:target="updatePassword"
                                        class="inline-flex justify-center px-4 py-2 ml-3 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-indigo-500 disabled:opacity-50">Update
                                        Password</button>
                                </div>
                            </form>
                        </section>
                        {{-- Delete Account Section (Placeholder) --}}
                        <section class="mt-6 overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                            <header
                                class="px-6 py-4 border-b border-red-200 bg-red-50 dark:bg-red-900/30 dark:border-red-700">
                                <h3 class="text-lg font-semibold text-red-800 dark:text-red-300">Delete Account</h3>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">Permanently delete your account.
                                    This action is irreversible.</p>
                            </header>
                            <div class="p-6">
                                <button type="button"
                                    class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-red-600 border border-transparent rounded-md cursor-not-allowed hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring focus:ring-red-300 disabled:opacity-75 opacity-80"
                                    disabled title="Delete Account (Feature not implemented)">
                                    Delete Account
                                </button>
                            </div>
                        </section>
                    </x-tab.panel>
                </div>

            </x-tab.container>

        </div>
    </div>

    {{-- The notification toast remains unchanged as it's already well-designed --}}
    <div x-data="{ show: false, message: '', type: '' }"
        @notify.window="message = $event.detail.message; type = $event.detail.type; show = true; setTimeout(() => show = false, 5000)"
        x-show="show" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        :class="{ 'bg-green-600 text-white': type === 'success', 'bg-red-600 text-white': type === 'error', 'bg-blue-600 text-white': type === 'info', 'bg-yellow-500 text-black': type === 'warning' }"
        class="fixed z-50 p-4 text-sm rounded-lg shadow-lg bottom-5 right-5" style="display: none;">
        <p x-text="message"></p>
    </div>
</div>