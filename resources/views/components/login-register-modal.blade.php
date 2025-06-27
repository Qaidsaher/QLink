<!--
    This component provides the X.com-style login/register functionality for guest users.
    It should be placed in your main layout file where you want the guest call-to-action to appear.
-->
@guest
<div x-data="{
        isOpen: false,
        formState: 'login', // Can be 'login' or 'register'

        openModal(initialState = 'login') {
            this.formState = initialState;
            this.isOpen = true;
            document.body.style.overflow = 'hidden'; // Lock body scroll
        },

        closeModal() {
            this.isOpen = false;
            document.body.style.overflow = 'auto'; // Restore body scroll
        },

        init() {
            // Watch for changes to isOpen to manage body scroll
            this.$watch('isOpen', value => {
                if (value) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = 'auto';
                }
            });

            // If form validation fails on the server, Laravel will redirect back with errors.
            // This snippet checks for errors and re-opens the modal to the correct form.
            @if ($errors->any())
                // Check if the errors belong to the login form
                const loginErrorKeys = ['email', 'password'];
                const hasLoginErrors = Object.keys({{ json_encode($errors->getMessages()) }}).some(key => loginErrorKeys.includes(key.split('.')[0]));

                if (hasLoginErrors && '{{ old('form_type') }}' === 'login') {
                    this.openModal('login');
                } else {
                    this.openModal('register');
                }
            @endif
        }
    }"
    @keydown.escape.window="closeModal()"
    class="w-full">

    <!-- Trigger Buttons (Styled like X.com's guest sidebar) -->
    <div class="p-4 space-y-3 border-t border-gray-200 dark:border-slate-700">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white">New to the platform?</h3>
        <p class="text-sm text-gray-600 dark:text-slate-400">
            Sign up now to get your own personalized timeline!
        </p>
        <button @click="openModal('register')" class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">
            Create account
        </button>
        <button @click="openModal('login')" class="w-full px-4 py-2 font-bold text-gray-800 bg-transparent border border-gray-300 rounded-full dark:text-white dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-800">
            Log in
        </button>
    </div>

    <!-- Modal Background Overlay and Panel -->
    <div x-show="isOpen"
         x-trap.noscroll="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-start justify-center p-4 pt-10 sm:pt-20 sm:items-center"
         style="display: none;">

        <!-- Modal Overlay with more blur -->
        <div @click="closeModal()" class="fixed inset-0 bg-black/60 backdrop-blur-md"></div>

        <!-- Modal Panel -->
        <div @click.away="closeModal()"
             x-show="isOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-md bg-white rounded-2xl dark:bg-black max-h-[90vh] flex flex-col shadow-2xl">

            <!-- Header with Close Button and Logo -->
            <div class="flex items-center flex-shrink-0 p-2">
                <button @click="closeModal()" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800" aria-label="Close modal">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <div class="flex-grow text-center">
                   <i class="text-3xl text-blue-500 fab fa-connectdevelop"></i>
                </div>
            </div>

            <!-- Content: Login and Register Forms -->
            <div class="flex-grow p-8 pt-2 overflow-y-auto">
               <h2 class="mb-8 text-3xl font-extrabold text-center text-gray-900 dark:text-white" x-text="formState === 'login' ? 'Sign in to your account' : 'Create your account'"></h2>

               {{-- This container manages the animation between the two forms --}}
               <div class="relative min-h-[350px]">
                    <!-- Login Form -->
                    <div x-show="formState === 'login'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200 absolute w-full"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="w-full">
                       <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="form_type" value="login">
                            <div>
                                <x-input-label for="login_email" value="Email" class="sr-only" />
                                <x-text-input class="w-full" id="login_email" type="email" name="email" :value="old('email')" required autofocus placeholder="Email" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                             <div>
                                <x-input-label for="login_password" value="Password" class="sr-only" />
                                <x-text-input class="w-full" id="login_password" type="password" name="password" required placeholder="Password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <button type="submit" class="w-full py-3 mt-4 font-bold text-white bg-gray-900 rounded-full dark:bg-white dark:text-black hover:opacity-90">Sign in</button>
                       </form>
                   </div>

                   <!-- Register Form -->
                    <div x-show="formState === 'register'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200 absolute w-full"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="w-full" style="display: none;">
                       <form method="POST" action="{{ route('register') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="form_type" value="register">
                            <div><x-text-input class="w-full" id="name" name="name" :value="old('name')" required placeholder="Name" /><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
                            <div><x-text-input class="w-full" id="username" name="username" :value="old('username')" required placeholder="Username" /><x-input-error :messages="$errors->get('username')" class="mt-2" /></div>
                            <div><x-text-input class="w-full" id="register_email" name="email" :value="old('email')" required placeholder="Email" /><x-input-error :messages="$errors->get('email')" class="mt-2" /></div>
                            <div><x-text-input class="w-full" id="register_password" name="password" required placeholder="Password" type="password" /><x-input-error :messages="$errors->get('password')" class="mt-2" /></div>
                            <div><x-text-input class="w-full" id="password_confirmation" name="password_confirmation" required placeholder="Confirm Password" type="password" /><x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" /></div>
                            <button type="submit" class="w-full py-3 mt-4 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">Create account</button>
                       </form>
                   </div>
               </div>

               <!-- Footer Link to Switch Forms -->
               <div class="mt-6 text-sm text-center">
                    <p x-show="formState === 'login'" class="text-gray-600 dark:text-slate-400">
                        Don't have an account?
                        <button @click="formState = 'register'" class="font-semibold text-blue-500 hover:underline">Sign up</button>
                    </p>
                    <p x-show="formState === 'register'" style="display: none;" class="text-gray-600 dark:text-slate-400">
                        Have an account already?
                        <button @click="formState = 'login'" class="font-semibold text-blue-500 hover:underline">Log in</button>
                    </p>
               </div>
            </div>
        </div>
    </div>
</div>
@endguest
