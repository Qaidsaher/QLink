<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="layout()" x-init="init()"
    :class="{ 'dark': isDarkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Saher Connect') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

    <!-- Styles & Scripts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 20px;
            border: 3px solid transparent;
            background-clip: content-box;
        }

        .dark ::-webkit-scrollbar-thumb {
            background-color: #4b5563;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    {{--
    <script>
    ... your original script ...
    </script> --}}

</head>

<body class="font-sans antialiased bg-white text-slate-800 dark:bg-black dark:text-slate-200">
 


    <div class="container relative flex min-h-screen mx-auto max-w-7xl">
        <!-- =============================================== -->
        <!-- Left Sidebar (Navigation) -->
        <!-- =============================================== -->
        <!-- Left Sidebar (Navigation) -->
        <!-- =============================================== -->
        <header
            class="fixed top-0 h-full flex flex-col justify-between border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-black z-50 md:flex transition-[width] duration-300 ease-in-out"
            :class="isSidebarOpen ? 'w-full md:w-[275px]' : 'w-0 md:w-[88px]'"
            x-show="isSidebarOpen || window.innerWidth >= 768" @keydown.escape.window="isSidebarOpen = false"
            x-trap.noscroll.inert="isSidebarOpen && window.innerWidth < 768" x-cloak>
            <div class="flex flex-col h-full overflow-y-auto">
                <div class="flex flex-col items-center p-2 space-y-2 xl:items-start">
                    <!-- Logo -->
                    <!-- Logo -->
                    <div class="flex items-center w-full h-16 border-b border-slate-200 dark:border-slate-800"
                        :class="isSidebarOpen ? 'px-6' : 'justify-center'">
                        <a href="{{ route('home') }}"
                            class="flex items-center text-blue-500 transition-colors hover:text-blue-600">
                            <i class="fab fa-connectdevelop fa-2x"></i>
                            <span class="ml-3 text-2xl font-bold" x-show="isSidebarOpen"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Saher</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <nav class="flex flex-col w-full space-y-1 text-xl">
                        <x-sidebar-nav-link :href="route('feed')" :active="request()->routeIs('feed')">
                            <x-slot:icon><i class="text-xl fas fa-home fa-fw w-7 h-7"></i></x-slot:icon>
                            <span x-show="isSidebarOpen">Home</span>
                        </x-sidebar-nav-link>

                        <x-sidebar-nav-link :href="route('search.index')" :active="request()->routeIs('search.index')">
                            <x-slot:icon><i class="text-xl fas fa-hashtag fa-fw w-7 h-7"></i></x-slot:icon>
                            <span x-show="isSidebarOpen">Explore</span>
                        </x-sidebar-nav-link>

                        <x-sidebar-nav-link :href="route('notifications')"
                            :active="request()->routeIs('notifications')">
                            <x-slot:icon><i class="text-xl far fa-bell fa-fw w-7 h-7"></i></x-slot:icon>
                            <div class="flex items-center justify-between w-full" x-show="isSidebarOpen">
                                <span>Notifications</span>
                                <span class="bg-red-500 text-white    p-0.5 rounded-full w-6 h-6 text-center"
                                    style="font-size: 14px;">3</span>
                            </div>
                        </x-sidebar-nav-link>

                        <x-sidebar-nav-link :href="route('messages')" :active="request()->routeIs('messages')">
                            <x-slot:icon><i class="text-xl far fa-envelope fa-fw w-7 h-7"></i></x-slot:icon>
                            <span x-show="isSidebarOpen">Messages</span>
                        </x-sidebar-nav-link>

                        @auth
                            <x-sidebar-nav-link :href="route('profile.show', Auth::user()->username)"
                                :active="request()->routeIs('profile.show') && request()->username == Auth::user()->username">
                                <x-slot:icon><i class="text-xl far fa-user fa-fw w-7 h-7"></i></x-slot:icon>
                                <span x-show="isSidebarOpen">Profile</span>
                            </x-sidebar-nav-link>
                        @endauth
                    </nav>

                    <!-- Post Button -->
                    <div class="w-full mt-4" :class="isSidebarOpen ? 'px-3' : 'flex justify-center'">
                        <a href="{{ route('posts.create') }}"
                            class="flex items-center justify-center font-bold text-white transition-all duration-200 bg-blue-500 rounded-full shadow-lg h-14 hover:bg-blue-600"
                            :class="isSidebarOpen ? 'w-full' : 'w-14'">
                            <span x-show="isSidebarOpen">Post</span>
                            <svg x-show="!isSidebarOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!--
    This container should be the last element inside your conversation list's main flex column.
    'mt-auto' will push it to the very bottom.
    A top border separates it from the conversation list above.
-->
            <div class="p-2 mt-auto border-t border-gray-200 dark:border-slate-700">
                @auth
                    <div x-data="{ open: false }" class="relative w-full">
                        <!-- User Profile Button -->
                        <button @click="open = !open"
                            class="flex items-center w-full p-2 text-left transition-colors duration-200 rounded-xl hover:bg-gray-100 focus:outline-none dark:hover:bg-slate-700">
                            <img class="object-cover w-10 h-10 rounded-full" src="{{ Auth::user()->avatarUrl() }}"
                                alt="{{ Auth::user()->name }}">

                            <!-- This part can be shown/hidden based on your sidebar state -->
                            <div class="flex-1 ml-3 overflow-hidden" x-show="isSidebarOpen">
                                <p class="text-sm font-semibold text-gray-800 truncate dark:text-gray-100">
                                    {{ Auth::user()->name }}
                                </p>
                                <p class="text-xs text-gray-500 truncate dark:text-slate-400">
                                    {{ '@' . Auth::user()->username }}
                                </p>
                            </div>

                            <!-- Three dots icon -->
                            <svg x-show="isSidebarOpen" class="w-5 h-5 ml-auto text-gray-500 shrink-0"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path
                                    d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                            </svg>
                        </button>

                        <!--
                                    Dropdown menu:
                                    - `top-full mt-2`: Positions it BELOW the button with a small margin.
                                    - `origin-top-right`: Makes the animation grow from the top right corner.
                                -->
                        <div x-show="open" @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-10 w-56 p-1 origin-top-right transform -translate-y-full bg-white border border-gray-200 rounded-lg shadow-lg -top-2 dark:bg-slate-800 dark:border-slate-700"
                            style="display: none;">

                            <a href="{{ route('profile.show', Auth::user()->username) }}"
                                class="flex items-center w-full px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 19.5a7.5 7.5 0 0115 0H4.5z" />
                                </svg>
                                My Profile
                            </a>
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center w-full px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.438.995s.145.755.438.995l1.003.827a1.125 1.125 0 01.26 1.431l-1.296 2.247a1.125 1.125 0 01-1.37.49l-1.217-.456c-.355-.133-.75-.072-1.075.124a6.57 6.57 0 01-.22.127c-.331.183-.581.495-.645.87l-.213 1.281c-.09.543-.56.94-1.11.94h-2.593c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.063-.374-.313-.686-.645-.87a6.52 6.52 0 01-.22-.127c-.324-.196-.72-.257-1.075-.124l-1.217.456a1.125 1.125 0 01-1.37-.49l-1.296-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.437-.995s-.145-.755-.437-.995l-1.004-.827a1.125 1.125 0 01-.26-1.431l1.296-2.247a1.125 1.125 0 011.37-.49l1.217.456c.355.133.75.072 1.075-.124.072-.044.146-.087.22-.127.332-.183.582-.495.645-.87l.213-1.281z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Settings
                            </a>

                            <div class="h-px my-1 bg-gray-200 dark:bg-slate-700"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-3 py-2 text-sm text-left text-red-600 rounded-md hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v3.75m1.5-3.75h4.5M12 15.75l3.75-3.75M12 15.75l-3.75-3.75" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col w-full p-1 space-y-2">
                        <a href="{{ route('login') }}"
                            class="flex items-center justify-center w-full py-2.5 text-sm font-semibold text-center text-blue-600 transition bg-blue-100 rounded-xl hover:bg-blue-200 dark:bg-blue-900/50 dark:text-blue-300 dark:hover:bg-blue-900">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3H7.5a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l-3-3m3 3l3-3m-3-3v6" />
                            </svg>
                            <span x-show="isSidebarOpen" class="ml-2">Login</span>
                        </a>
                    </div>
                @endauth
            </div>
        </header>

        <!-- Main Content Wrapper -->
        <div class="flex flex-1 w-full">
            <main
                class="flex-1 w-full max-w-[600px] min-h-screen border-x border-slate-200 dark:border-slate-800 transition-[margin-left] duration-300 ease-in-out"
                :class="isSidebarOpen ? 'md:ml-[275px]' : 'md:ml-[88px]'">
                @if (isset($header) || isset($title))
                    <header
                        class="sticky top-0 z-30 flex items-center px-4 border-b h-14 bg-white/80 backdrop-blur-md dark:bg-black/80 border-slate-200 dark:border-slate-800 sm:px-6">
                        <!-- Mobile Sidebar Toggle -->
                        <button @click.stop="isSidebarOpen = !isSidebarOpen" class="mr-4 md:hidden">
                            @auth
                                <img class="object-cover w-8 h-8 rounded-full" src="{{ Auth::user()->avatarUrl() }}"
                                    alt="Toggle Menu">
                            @else
                                <i class="text-xl fas fa-bars"></i>
                            @endauth
                        </button>
                        <!-- Header Title -->
                        <div class="flex-1 text-xl font-bold ">
                            {{ $title ?? $header }}
                        </div>
                    </header>
                @endif

                <!-- Slot for Page Content -->
                <div>{{ $slot }}</div>
            </main>

            <!-- Right Sidebar (Trends, Suggestions) -->
            <aside class="sticky top-0 flex-col hidden h-screen p-4 space-y-4 w-[350px] lg:flex shrink-0">
                @livewire('global-search')
                @livewire('trending-topics')
                @livewire('suggestions-follow')

                <!-- Footer and Theme Toggle -->
                <div class="p-2 mt-auto text-xs text-slate-500">
                    <div class="flex flex-wrap gap-x-3">
                        <a href="#" class="hover:underline">Terms of Service</a>
                        <a href="#" class="hover:underline">Privacy Policy</a>
                        <a href="#" class="hover:underline">Cookie Policy</a>
                        <button @click="toggleDarkMode" class="hover:underline">Toggle <span
                                x-text="isDarkMode ? 'Light' : 'Dark'"></span></button>
                    </div>
                    <p class="mt-2">© {{ date('Y') }} {{ config('app.name') }}</p>
                </div>
            </aside>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <nav
        class="fixed bottom-0 left-0 right-0 z-30 flex justify-around border-t h-14 bg-white/80 backdrop-blur-md dark:bg-black/80 border-slate-200 dark:border-slate-800 md:hidden">
        <a href="{{ route('feed') }}"
            class="flex items-center justify-center w-full {{ request()->routeIs('feed') ? 'text-blue-500' : 'text-slate-500' }} hover:text-blue-500"><i
                class="text-2xl fas fa-home"></i></a>
        <a href="{{ route('search.index') }}"
            class="flex items-center justify-center w-full {{ request()->routeIs('search.index') ? 'text-blue-500' : 'text-slate-500' }} hover:text-blue-500"><i
                class="text-2xl fas fa-search"></i></a>
        <a href="{{ route('notifications') }}"
            class="relative flex items-center justify-center w-full {{ request()->routeIs('notifications') ? 'text-blue-500' : 'text-slate-500' }} hover:text-blue-500"><i
                class="text-2xl far fa-bell"></i><span
                class="absolute top-2 right-[calc(50%-20px)] w-2 h-2 bg-red-500 rounded-full"></span></a>
        @auth
            <a href="{{ route('messages') }}"
                class="flex items-center justify-center w-full {{ request()->routeIs('messages') ? 'text-blue-500' : 'text-slate-500' }} hover:text-blue-500"><i
                    class="text-2xl far fa-envelope"></i></a>
        @endauth
    </nav>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

    @auth
        <script>
            const presenceController = {
                onlineUserIds: new Set(),

                init() {
                    this.connectToChannel();
                    this.updateAllAvatars();
                    document.addEventListener('livewire:load', () => {
                        this.updateAllAvatars();
                    });
                },

                updateAllAvatars() {
                    document.querySelectorAll('[data-user-id]').forEach(container => {
                        const id = Number(container.dataset.userId);
                        const isOnline = this.onlineUserIds.has(id);
                        const onlineDot = container.querySelector('.online-dot');
                        const offlineDot = container.querySelector('.offline-dot');
                        if (!onlineDot || !offlineDot) return;
                        onlineDot.classList.toggle('hidden', !isOnline);
                        offlineDot.classList.toggle('hidden', isOnline);
                    });
                },

                updateAvatarsById(userId, isOnline) {
                    document.querySelectorAll(`[data-user-id="${userId}"]`).forEach(container => {
                        const onlineDot = container.querySelector('.online-dot');
                        const offlineDot = container.querySelector('.offline-dot');
                        if (!onlineDot || !offlineDot) return;
                        onlineDot.classList.toggle('hidden', !isOnline);
                        offlineDot.classList.toggle('hidden', isOnline);
                    });
                },

                connectToChannel() {
                    Echo.join('online')
                        .here(users => {
                            this.onlineUserIds = new Set(users.map(u => u.id));
                            this.updateAllAvatars();
                        })
                        .joining(user => {
                            this.onlineUserIds.add(user.id);
                            this.updateAvatarsById(user.id, true);
                        })
                        .leaving(user => {
                            this.onlineUserIds.delete(user.id);
                            this.updateAvatarsById(user.id, false);
                        })
                        .error(err => console.error('Presence channel error:', err));
                },
            };

            document.addEventListener('DOMContentLoaded', () => {
                presenceController.init();
            });
        </script>
    @endauth
    <script>
        Fancybox.bind("[data-fancybox]", { /* options */ });

        function layout() {
            return {
                isSidebarOpen: window.innerWidth >= 1280, // Default to open on xl screens
                isDarkMode: false,
                init() {
                    this.isDarkMode = localStorage.getItem('darkMode') === 'true' ||
                        (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

                    this.$watch('isDarkMode', val => localStorage.setItem('darkMode', val));

                    window.addEventListener('resize', () => {
                        if (window.innerWidth < 768) {
                            this.isSidebarOpen = false;
                        } else if (window.innerWidth >= 1280) {
                            this.isSidebarOpen = true;
                        }
                    });
                },
                toggleDarkMode() {
                    this.isDarkMode = !this.isDarkMode;
                }
            }
        }
    </script>
</body>

</html>
