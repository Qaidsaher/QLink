<div>
    {{-- Main container for the entire page --}}
    <div class="max-w-4xl mx-auto">

        {{-- Sticky Header --}}
        <header class="sticky top-0 z-20 bg-white/80 dark:bg-black/80 backdrop-blur-sm">
            <div class="flex items-center gap-4 px-4 py-2 border-b border-gray-200 dark:border-slate-700">
                <a href="{{ route('profile.show', $user->username) }}" wire:navigate
                    class="p-2 transition-colors rounded-full hover:bg-gray-200 dark:hover:bg-slate-800">
                    <svg class="w-5 h-5 text-gray-800 dark:text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Edit Profile</h1>
            </div>
        </header>

        {{-- Main Content Area --}}
        <main class="p-4 space-y-8">

            <!-- ======================= Profile Information Section ======================= -->
            <section aria-labelledby="profile-info-heading">
                <form wire:submit.prevent="saveProfile">
                    <div class="p-3 bg-white rounded-sm shadow-sm dark:bg-slate-800/50">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h2 id="profile-info-heading" class="text-lg font-bold text-gray-900 dark:text-white">
                                    Public Profile</h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Update your account's profile
                                    information and images.</p>
                            </div>
                            <div class="flex items-center flex-shrink-0 gap-4">
                                <div x-data="{ shown: false, timeout: null }"
                                    x-init="$wire.on('profile-saved', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 3000) })"
                                    x-show="shown" x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-500"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-2" style="display: none;"
                                    class="flex items-center gap-2 px-4 py-2 mx-auto mt-4 text-sm font-medium text-green-800 bg-green-100 rounded-md shadow-md w-fit dark:bg-green-900 dark:text-green-200">
                                    <i class="text-lg fas fa-check-circle"></i>
                                    <span>Profile saved successfully</span>
                                </div>

                                <button type="submit" wire:loading.attr="disabled"
                                    class="px-5 py-2 text-sm font-bold text-white bg-black rounded-full dark:bg-white dark:text-black hover:opacity-90 disabled:opacity-70">
                                    <span wire:loading.remove wire:target="saveProfile, avatar, coverPhoto">Save</span>
                                    <span wire:loading wire:target="saveProfile, avatar, coverPhoto">Saving...</span>
                                </button>
                            </div>
                        </div>

                        <div x-data="{ coverPhotoPreview: '{{ $user->cover_photo_url }}', avatarPreview: '{{ $user->avatarUrl() }}' }"
                            class="relative mt-6">
                            <div class="relative h-48 bg-slate-200 dark:bg-slate-800 sm:h-56 rounded-xl">
                                <img x-show="coverPhotoPreview" :src="coverPhotoPreview"
                                    class="object-cover w-full h-full rounded-xl">
                                <div class="absolute inset-0 flex items-center justify-center gap-2 bg-black/30"><input
                                        type="file" wire:model="coverPhoto" x-ref="coverPhotoInput"
                                        @change="coverPhotoPreview = URL.createObjectURL($event.target.files[0])"
                                        class="hidden"><button type="button" @click="$refs.coverPhotoInput.click()"
                                        class="p-2 text-white transition rounded-full bg-black/50 hover:bg-black/70"><svg
                                            class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('coverPhoto')" class="mt-2" />
                            <div class="absolute p-1 bg-white rounded-full dark:bg-black -bottom-14 left-4">
                                <div class="relative w-28 h-28 sm:w-32 sm:h-32">
                                    <img :src="avatarPreview"
                                        class="object-cover w-full h-full border-4 border-white rounded-full dark:border-black">
                                    <div
                                        class="absolute inset-0 flex items-center justify-center transition-opacity rounded-full opacity-0 bg-black/30 hover:opacity-100">
                                        <input type="file" wire:model="avatar" x-ref="avatarInput"
                                            @change="avatarPreview = URL.createObjectURL($event.target.files[0])"
                                            class="hidden"><button type="button" @click="$refs.avatarInput.click()"
                                            class="p-2 text-white transition rounded-full bg-black/50 hover:bg-black/70"><svg
                                                class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('avatar')" class="mt-2" />

                        <div class="pt-20 space-y-6">
                            <div><x-input-label for="name" value="Name" /><x-text-input id="name" wire:model.lazy="name"
                                    class="w-full mt-1" /><x-input-error :messages="$errors->get('name')"
                                    class="mt-2" /></div>
                            <div>
                                <x-input-label for="username" value="Username" />
                                <div class="relative mt-1">
                                    <x-text-input id="username" wire:model.live.debounce.300ms="username"
                                        class="w-full" />
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <div wire:loading wire:target="username" class="animate-spin"><svg
                                                class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg></div>
                                        @if($usernameStatus === 'available' && !$errors->has('username'))<svg
                                                class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif($usernameStatus === 'taken' || $errors->has('username'))<svg
                                            class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>@endif
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>
                            <div><x-input-label for="bio" value="Bio" /><textarea id="bio" wire:model.lazy="bio"
                                    rows="4" maxlength="160"
                                    class="w-full mt-1 text-base text-gray-900 placeholder-gray-500 bg-gray-100 border-2 border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-800 dark:text-white dark:placeholder-gray-400"></textarea><x-input-error
                                    :messages="$errors->get('bio')" class="mt-2" /></div>
                            <div><x-input-label for="location" value="Location" /><x-text-input id="location"
                                    wire:model.lazy="location" class="w-full mt-1" /><x-input-error
                                    :messages="$errors->get('location')" class="mt-2" /></div>
                            <div><x-input-label for="website" value="Website" /><x-text-input id="website"
                                    wire:model.lazy="website" class="w-full mt-1"
                                    placeholder="https://example.com" /><x-input-error
                                    :messages="$errors->get('website')" class="mt-2" /></div>
                        </div>
                    </div>
                </form>
            </section>

            <!-- ======================= Notification Settings Section ======================= -->
            <section aria-labelledby="notification-settings-heading">
                <form wire:submit.prevent="saveNotificationSettings">
                    <div class="p-3 bg-white rounded-sm shadow-sm dark:bg-slate-800/50">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h2 id="notification-settings-heading"
                                    class="text-lg font-bold text-gray-900 dark:text-white">Email Notifications</h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Control how you receive email
                                    notifications.</p>
                            </div>
                            <div class="flex items-center flex-shrink-0 gap-4">
                                <div x-data="{ shown: false, timeout: null }" x-init="$wire.on('notifications-saved', () => { 
                                        clearTimeout(timeout); 
                                        shown = true; 
                                        timeout = setTimeout(() => { shown = false }, 3000) 
                                            })" x-show="shown" x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-500"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-2" style="display: none;"
                                    class="flex items-center gap-2 px-4 py-2 mx-auto mt-4 text-sm font-medium text-blue-800 bg-blue-100 rounded-md shadow-md w-fit dark:bg-blue-900 dark:text-blue-200">
                                    <i class="text-lg fas fa-bell"></i>
                                    <span>Notification preferences saved</span>
                                </div>

                                <button type="submit" wire:loading.attr="disabled"
                                    class="px-5 py-2 text-sm font-bold text-white bg-black rounded-full dark:bg-white dark:text-black hover:opacity-90 disabled:opacity-70">Save</button>
                            </div>
                        </div>
                        <div class="pt-6 mt-6 border-t border-gray-200 dark:border-slate-700">
                            <div class="space-y-6">
                                <div x-data="{ on: @entangle('notify_new_follower') }"
                                    class="flex items-center justify-between"><span
                                        class="flex flex-col flex-grow"><span
                                            class="text-sm font-medium text-gray-700 dark:text-slate-200">New
                                            Followers
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-slate-400">When
                                            someone new follows you.
                                        </span>
                                    </span>
                                    <button type="button" @click="on = !on"
                                        :class="on ? 'bg-blue-600' : 'bg-gray-200 dark:bg-slate-700'"
                                        class="relative inline-flex flex-shrink-0 h-6 transition-colors duration-200 ease-in-out border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        role="switch">
                                        <span :class="on ? 'translate-x-5' : 'translate-x-0'"
                                            class="inline-block w-5 h-5 transition duration-200 ease-in-out transform bg-white rounded-full shadow pointer-events-none ring-0">
                                        </span>
                                    </button>
                                </div>
                                <div x-data="{ on: @entangle('notify_new_comment') }"
                                    class="flex items-center justify-between">
                                    <span class="flex flex-col flex-grow">
                                        <span class="text-sm font-medium text-gray-700 dark:text-slate-200">Comments &
                                            Replies
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-slate-400">When
                                            someone comments on your posts.
                                        </span>
                                    </span>
                                    <button type="button" @click="on = !on"
                                        :class="on ? 'bg-blue-600' : 'bg-gray-200 dark:bg-slate-700'"
                                        class="relative inline-flex flex-shrink-0 h-6 transition-colors duration-200 ease-in-out border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        role="switch">
                                        <span :class="on ? 'translate-x-5' : 'translate-x-0'"
                                            class="inline-block w-5 h-5 transition duration-200 ease-in-out transform bg-white rounded-full shadow pointer-events-none ring-0">
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            <!-- ======================= Privacy and Safety Section ======================= -->
            <section aria-labelledby="privacy-settings-heading">
                <form wire:submit.prevent="savePrivacySettings">
                    <div class="p-3 bg-white rounded-sm shadow-sm dark:bg-slate-800/50">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h2 id="privacy-settings-heading"
                                    class="text-lg font-bold text-gray-900 dark:text-white">Privacy and Safety</h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Control who can interact with
                                    you.</p>
                            </div>
                            <div class="flex items-center flex-shrink-0 gap-4">
                                <div x-data="{ shown: false, timeout: null }" x-init="$wire.on('privacy-saved', () => {
        clearTimeout(timeout);
        shown = true;
        timeout = setTimeout(() => { shown = false }, 3000);
    })" x-show="shown" x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-500"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-2" style="display: none;"
                                    class="flex items-center gap-2 px-4 py-2 mx-auto mt-4 text-sm font-medium text-purple-800 bg-purple-100 rounded-md shadow-md w-fit dark:bg-purple-900 dark:text-purple-200">
                                    <i class="text-lg fas fa-shield-alt"></i>
                                    <span>Privacy settings saved</span>
                                </div>

                                <button type="submit" wire:loading.attr="disabled"
                                    class="px-5 py-2 text-sm font-bold text-white bg-black rounded-full dark:bg-white dark:text-black hover:opacity-90 disabled:opacity-70">Save</button>
                            </div>
                        </div>
                        <div class="pt-6 mt-6 border-t border-gray-200 dark:border-slate-700">
                            <div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white">Direct Messages</h3>
                                <p class="text-sm text-gray-600 dark:text-slate-400">Allow message requests from:</p>
                                <fieldset class="mt-4">
                                    <legend class="sr-only">Message request settings</legend>
                                    <div class="space-y-4">
                                        <div class="flex items-center"><input id="messages-everyone"
                                                wire:model="messages_from" value="everyone" type="radio"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"><label
                                                for="messages-everyone"
                                                class="block ml-3 text-sm font-medium text-gray-700 dark:text-slate-300">Everyone</label>
                                        </div>
                                        <div class="flex items-center"><input id="messages-following"
                                                wire:model="messages_from" value="following" type="radio"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"><label
                                                for="messages-following"
                                                class="block ml-3 text-sm font-medium text-gray-700 dark:text-slate-300">People
                                                you follow</label></div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            <!-- ======================= Update Password Section ======================= -->
            <section aria-labelledby="update-password-heading">
                <form wire:submit.prevent="updatePassword">
                    <div class="p-3 bg-white rounded-sm shadow-sm dark:bg-slate-800/50">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h2 id="update-password-heading"
                                    class="text-lg font-bold text-gray-900 dark:text-white">Update Password</h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Ensure your account is using a
                                    long, random password to stay secure.</p>
                            </div>
                            <div class="flex items-center flex-shrink-0 gap-4">
                                <div x-data="{ shown: false, timeout: null }" x-init="$wire.on('password-updated', () => {
                                            clearTimeout(timeout);
                                            shown = true;
                                            timeout = setTimeout(() => { shown = false }, 3000);
                                    })" x-show="shown" x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-500"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-2" style="display: none;"
                                    class="flex items-center gap-2 px-4 py-2 mx-auto mt-4 text-sm font-medium text-gray-800 bg-gray-100 rounded-md shadow-md w-fit dark:bg-gray-800 dark:text-gray-100">
                                    <i class="text-lg text-gray-600 fas fa-lock dark:text-gray-300"></i>
                                    <span>Password updated successfully</span>
                                </div>

                                <button type="submit" wire:loading.attr="disabled"
                                    class="px-5 py-2 text-sm font-bold text-white bg-black rounded-full dark:bg-white dark:text-black hover:opacity-90 disabled:opacity-70">Update</button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-6 mt-6 md:grid-cols-2">
                            <div><x-input-label for="current_password" value="Current Password" /><x-text-input
                                    id="current_password" type="password" wire:model.lazy="current_password"
                                    class="w-full mt-1" /><x-input-error :messages="$errors->get('current_password')"
                                    class="mt-2" /></div>
                            <div></div>{{-- Spacer --}}
                            <div><x-input-label for="new_password" value="New Password" /><x-text-input
                                    id="new_password" type="password" wire:model.lazy="new_password"
                                    class="w-full mt-1" /><x-input-error :messages="$errors->get('new_password')"
                                    class="mt-2" /></div>
                            <div><x-input-label for="new_password_confirmation"
                                    value="Confirm New Password" /><x-text-input id="new_password_confirmation"
                                    type="password" wire:model.lazy="new_password_confirmation" class="w-full mt-1" />
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            <!-- ======================= Delete Account Section ======================= -->
            <section aria-labelledby="delete-account-heading">
                <div class="p-6 bg-red-50 dark:bg-red-900/10 rounded-xl">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <h2 id="delete-account-heading" class="text-lg font-bold text-red-700 dark:text-red-400">
                                Delete Account</h2>
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400/80">Once your account is deleted, all
                                of its resources and data will be permanently deleted.</p>
                        </div>
                        <div class="flex-shrink-0">
                            <button wire:click="confirmAccountDeletion"
                                class="px-5 py-2 text-sm font-bold text-white bg-red-600 rounded-full hover:bg-red-700">Delete
                                Account</button>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- ======================= Account Deletion Modal ======================= -->
        <div x-data="{ show: @entangle('confirmingAccountDeletion') }" x-show="show" x-trap.noscroll="show"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-6 lg:px-8"
            style="display: none;">
            <!-- Backdrop -->
            <div @click="show = false" class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-md"></div>

            <!-- Modal Card -->
            <div @click.away="show = false"
                class="relative z-50 w-full max-w-md p-8 transition-all transform bg-white shadow-xl rounded-2xl dark:bg-slate-900 sm:p-10">
                <!-- Icon -->
                <div class="flex justify-center mb-4">
                    <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-full dark:bg-red-900">
                        <i class="text-2xl text-red-600 fas fa-exclamation-triangle dark:text-red-300"></i>
                    </div>
                </div>

                <!-- Title -->
                <h3 class="text-2xl font-bold text-center text-gray-900 dark:text-white">Delete Your Account</h3>
                <p class="mt-2 text-sm text-center text-gray-600 dark:text-gray-400">
                    This action is <span class="font-semibold text-red-500">permanent</span> and cannot be undone.
                    Please confirm by typing your password.
                </p>

                <!-- Form -->
                <form wire:submit.prevent="deleteAccount" class="mt-6 space-y-5">
                    <!-- Password Input -->
                    <div>
                        <x-text-input id="delete_password" type="password" wire:model.lazy="delete_password"
                            class="w-full text-center" placeholder="Enter your password" />
                        <x-input-error :messages="$errors->get('delete_password')" class="mt-2" />
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <!-- Delete Button -->
                        <button type="submit" wire:loading.attr="disabled"
                            class="flex items-center justify-center w-full gap-2 px-4 py-3 font-bold text-white transition duration-200 bg-red-600 rounded-full hover:bg-red-700 disabled:opacity-70">
                            <i class="fas fa-trash-alt"></i>
                            <span>Yes, Delete My Account</span>
                        </button>

                        <!-- Cancel Button -->
                        <button type="button" @click="show = false"
                            class="flex items-center justify-center w-full gap-2 px-4 py-3 font-bold text-gray-800 transition duration-200 border border-gray-300 rounded-full dark:text-white dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-800">
                            <i class="fas fa-times"></i>
                            <span>Cancel</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>