<div>
    {{-- This component is only rendered for guest users --}}
    @guest
    <div class="p-4 space-y-3 border-t border-gray-200 dark:border-slate-700">
        <h3 class="hidden font-bold text-gray-800 text-md dark:text-white xl:block">
            New to the  {{ config('app.name', 'QLink') }} platform?
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
                    <div class="flex-grow text-center"><i class="text-3xl text-blue-500 fab fa-connectdevelop"></i></div>
                </div>

                <div class="flex-grow p-8 pt-2 overflow-y-auto">
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
                                    class="w-full py-3 mt-4 font-bold text-white bg-gray-900 rounded-full dark:bg-white dark:text-black hover:opacity-90">Sign
                                    in</button>
                            </form>
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
                                    class="w-full py-3 mt-4 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">Create
                                    account</button>
                            </form>
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
