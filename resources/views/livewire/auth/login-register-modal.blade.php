<div>
    {{-- This component is only rendered for guest users --}}
    @guest
        <div class="p-4 space-y-3 border-t border-gray-200 dark:border-slate-700">
            <h3 class="hidden font-bold text-gray-800 text-md dark:text-white xl:block">
                New to the {{ config('app.name', 'QLink') }} platform?
            </h3>

            <p class="hidden text-sm text-gray-600 dark:text-slate-400 xl:block">
                Sign up now to get your own personalized timeline!
            </p>

            <!-- Create Account Button -->
            <button wire:click="open('register')"
                class="flex items-center justify-center w-full gap-2 px-4 py-2 font-bold text-white transition-all bg-blue-500 rounded-full hover:bg-blue-600">
                <i class="text-lg fas fa-user-plus"></i>
                <span class="hidden xl:inline">Create account</span>
            </button>

            <!-- Login Button -->
            <button wire:click="open('login')"
                class="flex items-center justify-center w-full gap-2 px-4 py-2 font-bold text-gray-800 bg-transparent border border-gray-300 rounded-full dark:text-white dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-800">
                <i class="text-lg fas fa-sign-in-alt"></i>
                <span class="hidden xl:inline">Log in</span>
            </button>
        </div>
    @endguest


    @if($isOpen)
        <div x-data="{ isOpen: @entangle('isOpen') }" x-show="isOpen" @keydown.escape.window="isOpen = false"
            x-trap.noscroll="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4">

            <div wire:click="closeModal" class="fixed inset-0 bg-black/60 backdrop-blur-md"></div>

            <div @click.away="closeModal()"
                class="relative w-full max-w-md bg-white rounded-2xl dark:bg-black max-h-[85vh] flex flex-col shadow-2xl">

                <div class="flex items-center flex-shrink-0 p-2">
                    <button wire:click="closeModal" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800"
                        aria-label="Close modal">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div class="flex justify-center flex-grow text-center">
                        <a href="{{ route('home') }}"
                            class="flex items-center text-3xl font-semibold text-blue-600 transition-all duration-300 rounded-full ">
                            <img src="{{ asset('/images/logo1.svg') }}" class="object-fill w-16 h-16 sm:w-12 sm:h-12" />
                            <span class="hidden xl:inline" style="font-family: sans-serif;">
                                {{ config('app.name', 'QLink') }}</span>
                        </a>

                    </div>
                </div>

                <div class="flex-grow p-8 pt-1 overflow-y-auto">
                    <h2 class="mb-8 text-3xl font-extrabold text-center text-gray-900 dark:text-white">
                        {{ $formState === 'login' ? 'Sign in to your account' : 'Create your account' }}
                    </h2>

                    {{-- The content area for the forms --}}
                    <div class="w-full">
                        <div x-show="$wire.formState === 'login'" x-transition:opacity.duration.500ms>
                            <form wire:submit.prevent="login" class="space-y-6">
                                <div>
                                    <x-text-input class="w-full" wire:model.lazy="loginEmail" id="login_email" type="email"
                                        name="email" required placeholder="Email" />
                                    <x-input-error :messages="$errors->get('loginEmail')" class="mt-2" />
                                </div>
                                <div>
                                    <x-text-input class="w-full" wire:model.lazy="loginPassword" id="login_password"
                                        type="password" name="password" required placeholder="Password" />
                                    <x-input-error :messages="$errors->get('loginPassword')" class="mt-2" />
                                </div>
                                <button type="submit"
                                    class="w-full py-3 mt-4 font-bold text-white bg-gray-900 rounded-full dark:bg-white dark:text-black hover:opacity-90 relative h-[48px]"
                                    wire:target="login" wire:loading.attr="disabled">

                                    {{-- Normal Text --}}
                                    <span wire:loading.remove wire:target="login">
                                        Sign in
                                    </span>

                                    {{-- Spinner Centered --}}
                                    <div wire:loading wire:target="login" class="">
                                        <svg class="w-5 h-5 text-white animate-spin dark:text-black"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                    </div>
                                </button>

                            </form>
                            <a href={{ route('google.login') }}
                                class="inline-flex items-center justify-center w-full px-4 py-2 my-1 font-semibold text-gray-700 bg-white border border-gray-300 rounded-full shadow-sm dark:bg-slate-800 dark:border-slate-600 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-700">
                                <svg class="w-5 h-5 mr-2 text-red-500" viewBox="0 0 488 512" fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M488 261.8c0-17.8-1.5-35-4.4-51.6H249v97.8h134.2c-5.8 31.4-23.2 57.8-49.6 75.6l80 62.3c46.8-43.1 74.4-106.7 74.4-184.1zM249 480c66.5 0 122.3-22 163.1-59.6l-80-62.3c-22.2 15-50.8 23.9-83.1 23.9-63.8 0-117.8-43.1-137.2-101.2H30.9v63.7C71.8 432.5 153.8 480 249 480zM111.8 280.8c-5.6-16.5-8.8-34-8.8-52s3.2-35.5 8.8-52V113.9H30.9C11.1 153.6 0 199.5 0 248s11.1 94.4 30.9 134.1l80.9-63.3zM249 97.8c36 0 68.4 12.4 93.8 36.7l70.2-70.2C368.1 25.3 311.5 0 249 0 153.8 0 71.8 47.5 30.9 113.9l80.9 63.7C131.2 140.9 185.2 97.8 249 97.8z" />
                                </svg> Sign in with Google
                            </a>
                        </div>

                        <div x-show="$wire.formState === 'register'" x-transition:opacity.duration.500ms
                            style="display: none;">
                            <form wire:submit.prevent="register" class="space-y-6">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <!-- Name -->
                                    <div>
                                        <x-text-input class="w-full" wire:model.lazy="name" id="name" required
                                            placeholder="Name" />
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>

                                    <!-- Username with validation + status icons -->
                                    <div class="relative">
                                        <x-text-input class="w-full" wire:model.live.debounce.300ms="username" id="username"
                                            required placeholder="Username" />

                                        <!-- Loading or Status Icon -->
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <!-- Spinner -->
                                            <div wire:loading wire:target="username" class="animate-spin">
                                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                </svg>
                                            </div>

                                            <!-- Success -->
                                            @if($usernameStatus === 'available' && !$errors->has('username'))
                                                <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @elseif($usernameStatus === 'taken' || $errors->has('username'))
                                                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            @endif
                                        </div>

                                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                    </div>
                                </div>

                                <div><x-text-input class="w-full" wire:model.lazy="email" id="register_email" type="email"
                                        required placeholder="Email" /><x-input-error :messages="$errors->get('email')"
                                        class="mt-2" /></div>
                                <div><x-text-input class="w-full" wire:model.lazy="password" id="register_password"
                                        type="password" required placeholder="Password" /><x-input-error
                                        :messages="$errors->get('password')" class="mt-2" /></div>
                                <div><x-text-input class="w-full" wire:model.lazy="password_confirmation"
                                        id="password_confirmation" type="password" required
                                        placeholder="Confirm Password" /></div>
                                <button type="submit"
                                    class="w-full py-3 mt-4 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600 relative h-[48px]"
                                    wire:target="register" wire:loading.attr="disabled">

                                    {{-- Normal Text --}}
                                    <span wire:loading.remove wire:target="register">
                                        Create account
                                    </span>

                                    {{-- Spinner while loading --}}
                                    <div wire:loading wire:target="register">
                                        <svg class="w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                    </div>
                                </button>

                            </form>
                            <a href={{ route('google.login') }}
                                class="inline-flex items-center justify-center w-full px-4 py-2 my-1 font-semibold text-gray-700 bg-white border border-gray-300 rounded-full shadow-sm dark:bg-slate-800 dark:border-slate-600 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-700">
                                <svg class="w-5 h-5 mr-2 text-red-500" viewBox="0 0 488 512" fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M488 261.8c0-17.8-1.5-35-4.4-51.6H249v97.8h134.2c-5.8 31.4-23.2 57.8-49.6 75.6l80 62.3c46.8-43.1 74.4-106.7 74.4-184.1zM249 480c66.5 0 122.3-22 163.1-59.6l-80-62.3c-22.2 15-50.8 23.9-83.1 23.9-63.8 0-117.8-43.1-137.2-101.2H30.9v63.7C71.8 432.5 153.8 480 249 480zM111.8 280.8c-5.6-16.5-8.8-34-8.8-52s3.2-35.5 8.8-52V113.9H30.9C11.1 153.6 0 199.5 0 248s11.1 94.4 30.9 134.1l80.9-63.3zM249 97.8c36 0 68.4 12.4 93.8 36.7l70.2-70.2C368.1 25.3 311.5 0 249 0 153.8 0 71.8 47.5 30.9 113.9l80.9 63.7C131.2 140.9 185.2 97.8 249 97.8z" />
                                </svg> Sign in with Google
                            </a>
                        </div>
                    </div>

                    <div class="mt-6 text-sm text-center">
                        @if($formState === 'login')
                            <p class="text-gray-600 dark:text-slate-400">
                                Don't have an account?
                                <button wire:click="switchForm('register')"
                                    class="font-semibold text-blue-500 hover:underline">Sign up</button>
                            </p>
                        @else
                            <p class="text-gray-600 dark:text-slate-400">
                                Have an account already?
                                <button wire:click="switchForm('login')" class="font-semibold text-blue-500 hover:underline">Log
                                    in</button>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>