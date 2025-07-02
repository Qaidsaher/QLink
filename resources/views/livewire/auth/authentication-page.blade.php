<div class="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-slate-900">
    <div class="w-full max-w-5xl m-4 bg-white shadow-2xl dark:bg-black rounded-2xl lg:flex">

        <!-- Left Side: Image Carousel -->
        <div class="relative hidden w-1/2 lg:block">
            <div x-data="{
                    slides: [
                        { 
                            img: 'https://images.unsplash.com/photo-1554629947-334ff61d85dc?q=80&w=2938&auto=format&fit=crop',
                            title: 'Connect with the World',
                            desc: 'Share your moments, discover new stories, and connect with a global community.'
                        },
                        { 
                            img: 'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?q=80&w=2940&auto=format&fit=crop',
                            title: 'Explore Your Interests',
                            desc: 'From trending topics to niche hobbies, find conversations that matter to you.'
                        },
                        { 
                            img: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=2940&auto=format&fit=crop',
                            title: 'Express Yourself',
                            desc: 'Customize your profile, share rich media, and let your personality shine.'
                        }
                    ],
                    active: 0,
                    autoplay() {
                        setInterval(() => { this.active = (this.active + 1) % this.slides.length }, 5000);
                    }
                }" x-init="autoplay()" class="w-full h-full">

                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="active === index" x-transition:enter="transition ease-out duration-1000"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-1000" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="absolute inset-0">
                        <img :src="slide.img" class="object-cover w-full h-full rounded-l-2xl" alt="Carousel Image">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute p-8 text-white bottom-8">
                            <h2 class="text-3xl font-extrabold" x-text="slide.title"></h2>
                            <p class="mt-2 text-white/80" x-text="slide.desc"></p>
                        </div>
                    </div>
                </template>

                <div class="absolute flex space-x-2 bottom-4 right-8">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="active = index"
                            :class="{'bg-white': active === index, 'bg-white/50': active !== index}"
                            class="w-2 h-2 rounded-full"></button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Right Side: Auth Form -->
        <div class="flex flex-col justify-center w-full p-6 lg:w-1/2 sm:p-12">
            <div class="text-center">
                <a href="{{ route('home') }}"
                    class="flex items-center justify-center w-full text-3xl font-semibold text-blue-600 transition-all duration-300 rounded-full">
                    <img src="{{ asset('/images/logo1.svg') }}" class="object-fill w-16 h-16 sm:w-12 sm:h-12" />
                    <span class="hidden xl:inline" style="font-family: sans-serif;">
                        {{ config('app.name', 'QLink') }}</span>
                </a>
                <h2 class="mt-4 text-3xl font-extrabold text-gray-900 dark:text-white">
                    {{ $formState === 'login' ? 'Welcome Back!' : 'Join Today' }}
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-slate-400">
                    @if($formState === 'login')
                        Don't have an account? <button wire:click="switchForm('register')"
                            class="font-medium text-blue-500 hover:underline">Sign up</button>
                    @else
                        Already have an account? <button wire:click="switchForm('login')"
                            class="font-medium text-blue-500 hover:underline">Sign in</button>
                    @endif
                </p>
            </div>

            <div class="mt-8">
                {{-- Forms are now displayed directly, not absolutely positioned, allowing the container to naturally
                fit their height --}}

                <!-- Login Form -->
                <form wire:submit.prevent="login" class="space-y-6" x-show="$wire.formState === 'login'"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100">
                    <div><x-text-input class="w-full" wire:model.lazy="loginIdentifier" id="login_identifier"
                            placeholder="Email or Username" /><x-input-error :messages="$errors->get('loginIdentifier')"
                            class="mt-2" /></div>
                    <div><x-text-input class="w-full" wire:model.lazy="loginPassword" id="login_password"
                            type="password" placeholder="Password" /><x-input-error
                            :messages="$errors->get('loginPassword')" class="mt-2" /></div>
                    <button type="submit"
                        class="w-full py-3 font-bold text-white bg-gray-900 rounded-full dark:bg-white dark:text-black hover:opacity-90">Sign
                        In</button>
                </form>

                <!-- Register Form -->
                <form wire:submit.prevent="register" class="space-y-6" x-show="$wire.formState === 'register'"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" style="display: none;">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-text-input class="w-full" wire:model.lazy="name" id="name"
                                placeholder="Full Name" /><x-input-error :messages="$errors->get('name')"
                                class="mt-2" />
                        </div>
                        <div>
                            <div class="relative">
                                <x-text-input class="w-full" wire:model.live.debounce.300ms="username" id="username"
                                    placeholder="Username" />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <div wire:loading wire:target="username" class="animate-spin"><svg
                                            class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>@endif
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('username')" class="mt-2 sm:col-span-2" />
                        </div>
                    </div>
                    <div><x-text-input class="w-full" wire:model.lazy="email" id="register_email" type="email"
                            placeholder="Email Address" /><x-input-error :messages="$errors->get('email')"
                            class="mt-2" /></div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div><x-text-input class="w-full" wire:model.lazy="password" id="register_password"
                                type="password" placeholder="Password" /><x-input-error
                                :messages="$errors->get('password')" class="mt-2" /></div>
                        <div><x-text-input class="w-full" wire:model.lazy="password_confirmation"
                                id="password_confirmation" type="password" placeholder="Confirm Password" /></div>
                    </div>
                    <button type="submit"
                        class="w-full py-3 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">Create
                        Account</button>
                </form>

                <!-- Divider -->
                <div class="relative flex items-center py-2 pt-6">
                    <div class="flex-grow border-t border-gray-200 dark:border-slate-700"></div>
                    <span class="flex-shrink mx-4 text-xs text-gray-500 uppercase">Or</span>
                    <div class="flex-grow border-t border-gray-200 dark:border-slate-700"></div>
                </div>

                <!-- Social Logins -->
                <div class="space-y-3">
                    <button wire:click="redirectToProvider('google')"
                        class="inline-flex items-center justify-center w-full px-4 py-2 font-semibold text-gray-700 bg-white border border-gray-300 rounded-full shadow-sm dark:bg-slate-800 dark:border-slate-600 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-700">
                        <svg class="w-5 h-5 mr-2 text-red-500" viewBox="0 0 488 512" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M488 261.8c0-17.8-1.5-35-4.4-51.6H249v97.8h134.2c-5.8 31.4-23.2 57.8-49.6 75.6l80 62.3c46.8-43.1 74.4-106.7 74.4-184.1zM249 480c66.5 0 122.3-22 163.1-59.6l-80-62.3c-22.2 15-50.8 23.9-83.1 23.9-63.8 0-117.8-43.1-137.2-101.2H30.9v63.7C71.8 432.5 153.8 480 249 480zM111.8 280.8c-5.6-16.5-8.8-34-8.8-52s3.2-35.5 8.8-52V113.9H30.9C11.1 153.6 0 199.5 0 248s11.1 94.4 30.9 134.1l80.9-63.3zM249 97.8c36 0 68.4 12.4 93.8 36.7l70.2-70.2C368.1 25.3 311.5 0 249 0 153.8 0 71.8 47.5 30.9 113.9l80.9 63.7C131.2 140.9 185.2 97.8 249 97.8z" />
                        </svg> Sign in with Google
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>