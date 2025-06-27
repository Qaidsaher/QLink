<header
    x-data="{ isSidebarOpen: true }"
    class="sticky top-0 z-40 flex-col justify-between hidden h-screen pr-2 md:flex border-slate-200 dark:border-slate-800 md:w-[88px] xl:w-[275px] transition-all duration-300 ">

    <div class="flex flex-col items-center flex-grow h-full p-1 overflow-y-auto xl:items-start">
        <!-- Logo -->
        <div class="w-full p-1 my-2 bg-gray-100 rounded-full dark:bg-slate-900">
            <a href="{{ route('home') }}"
                class="flex items-center text-3xl font-semibold text-blue-600 transition-all duration-300 rounded-full ">
                {{-- <i class="text-3xl fab fa-connectdevelop"></i>
                <span class="hidden xl:inline">SaherConnect</span> --}}
                {{-- <x-application-logo class="w-10 h-10"/> --}}
                <img src="{{ asset('/images/logo1.svg') }}" class="object-fill w-16 h-16" />
                <span class="hidden xl:inline" style="font-family: sans-serif;"> {{ config('app.name', 'QLink') }}</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav
            class="flex flex-col items-center w-full p-2 space-y-1 text-xl bg-gray-100 xl:items-start dark:bg-slate-900 rounded-xl">
            <x-sidebar-nav-link :href="route('feed')" :active="request()->routeIs('feed')">
                <x-slot:icon>
                    <i class="text-xl fas fa-home fa-fw w-7 h-7"></i>
                    <span class="hidden xl:inline">Home</span>
                </x-slot:icon>
            </x-sidebar-nav-link>

            <x-sidebar-nav-link :href="route('search.index')" :active="request()->routeIs('search.index')">
                <x-slot:icon>
                    <i class="text-xl fas fa-search fa-fw w-7 h-7"></i>
                    <span class="hidden xl:inline">Explore</span>
                </x-slot:icon>
            </x-sidebar-nav-link>

            <x-sidebar-nav-link :href="route('notifications')" :active="request()->routeIs('notifications')">
                <x-slot:icon>
                    <i class="text-xl far fa-bell fa-fw w-7 h-7"></i>
                    <span class="hidden xl:inline">Notifications</span>
                </x-slot:icon>
            </x-sidebar-nav-link>

            <x-sidebar-nav-link :href="route('messages')" :active="request()->routeIs('messages')">
                <x-slot:icon>
                    <i class="text-xl far fa-envelope fa-fw w-7 h-7"></i>
                    <span class="hidden xl:inline">Messages</span>
                </x-slot:icon>
            </x-sidebar-nav-link>

            @auth
                <x-sidebar-nav-link :href="route('profile.show', Auth::user()->username)"
                    :active="request()->routeIs('profile.show') && request()->username == Auth::user()->username">
                    <x-slot:icon>
                        <i class="text-xl far fa-user fa-fw w-7 h-7"></i>
                        <span class="hidden xl:inline">Profile</span>
                    </x-slot:icon>
                </x-sidebar-nav-link>
            @endauth
        </nav>

        <!-- Post Button -->
        <div class="w-full mt-4 xl:prg-4">
            <a href="{{ route('posts.create') }}" wire:navigate
                class="flex items-center justify-center h-12 font-bold text-white transition-all duration-200 bg-blue-500 rounded-full shadow-sm xl:w-full hover:bg-blue-600">
                <i class="text-lg xl:hidden fas fa-feather-alt"></i>
                <span class="flex items-center hidden gap-2 xl:inline">
                    <i class="fas fa-feather-alt"></i> Post
                </span>
            </a>
        </div>
    </div>

    <!-- Profile/Logout Section -->
    <div class="pb-4 mt-auto xl:p-2">
        @auth
            <div x-data="{ open: false }" class="relative flex justify-center w-full py-2 border-t border-gray-200 dark:border-slate-700">
                <button @click="open = !open"
                    class="flex items-center p-0 transition-colors bg-gray-100 rounded-full xl:w-full xl:p-2 hover:bg-gray-200 dark:bg-slate-900">
                    <x-avatar :user="Auth::user()" class="m-0" />
                    <div class="flex-1 hidden ml-3 overflow-hidden text-left xl:block">
                        <p class="text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate dark:text-slate-400">
                            @<span>{{ Auth::user()->username }}</span>
                        </p>
                    </div>
                    <svg class="hidden w-5 h-5 ml-auto text-gray-500 xl:block shrink-0" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                    </svg>
                </button>
                <div x-show="open" @click.outside="open = false" x-transition
                    class="absolute p-1 mb-2 origin-bottom bg-white border border-gray-200 shadow-lg bottom-full w-72 rounded-2xl dark:bg-slate-900 dark:border-slate-700"
                    style="display: none;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full px-3 py-3 text-sm font-bold text-left text-red-600 rounded-lg hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                            Log out @<span>{{ Auth::user()->username }}</span>
                        </button>
                    </form>
                </div>
            </div>
        @endauth

        @guest
            @livewire('auth.login-register-modal')
        @endguest
    </div>
</header>
