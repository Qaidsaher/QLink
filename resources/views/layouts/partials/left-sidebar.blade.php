<aside class="fixed top-0 z-40 flex flex-col justify-between hidden w-20 h-full transition-all duration-300 ease-in-out bg-white border-r border-gray-200 lg:w-64 dark:bg-gray-900 dark:border-gray-800 md:flex"
       x-data="{ sidebarOpen: true }"
       :class="{'lg:w-64': sidebarOpen, 'lg:w-20': !sidebarOpen}">
    <div>
        <!-- Logo -->
        <div class="flex items-center h-16 border-b border-gray-200 dark:border-gray-800" :class="sidebarOpen ? 'justify-start px-6' : 'justify-center'">
            <a href="{{ route('home') }}" class="transition-colors text-primary-DEFAULT hover:text-primary-dark">
                <i class="fab fa-connectdevelop fa-2x" :class="sidebarOpen ? 'lg:mr-2' : ''"></i>
                <span class="text-xl font-bold" x-show="sidebarOpen" :class="{'hidden lg:inline': sidebarOpen}">Saher</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="px-2 mt-6 space-y-1" :class="sidebarOpen ? 'lg:px-4' : ''">
            <x-sidebar-nav-link :href="route('feed')" :active="request()->routeIs('feed')">
                <i class="text-lg transition-transform fas fa-home fa-fw group-hover:scale-110" :class="sidebarOpen ? 'lg:mr-3' : 'mx-auto'"></i>
                <span x-show="sidebarOpen" :class="{'hidden lg:inline': sidebarOpen}">Home</span>
            </x-sidebar-nav-link>
            <x-sidebar-nav-link href="#"> {{-- Placeholder --}}
                <i class="text-lg transition-transform fas fa-hashtag fa-fw group-hover:scale-110" :class="sidebarOpen ? 'lg:mr-3' : 'mx-auto'"></i>
                <span x-show="sidebarOpen" :class="{'hidden lg:inline': sidebarOpen}">Explore</span>
            </x-sidebar-nav-link>
            <x-sidebar-nav-link href="#">
                <i class="text-lg transition-transform far fa-bell fa-fw group-hover:scale-110" :class="sidebarOpen ? 'lg:mr-3' : 'mx-auto'"></i>
                <span x-show="sidebarOpen" :class="{'hidden lg:inline': sidebarOpen}">Notifications</span>
                <span class="ml-auto bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full" x-show="sidebarOpen" :class="{'hidden lg:inline': sidebarOpen}">3</span>
            </x-sidebar-nav-link>
            <x-sidebar-nav-link href="#">
                <i class="text-lg transition-transform far fa-envelope fa-fw group-hover:scale-110" :class="sidebarOpen ? 'lg:mr-3' : 'mx-auto'"></i>
                <span x-show="sidebarOpen" :class="{'hidden lg:inline': sidebarOpen}">Messages</span>
            </x-sidebar-nav-link>
            @auth
            {{-- <x-sidebar-nav-link :href="route('profile.show', Auth::user()->username)" :active="request()->routeIs('profile.show') && request()->route('user') && request()->route('user')->id === Auth::id()">
                <i class="text-lg transition-transform far fa-user fa-fw group-hover:scale-110" :class="sidebarOpen ? 'lg:mr-3' : 'mx-auto'"></i>
                <span x-show="sidebarOpen" :class="{'hidden lg:inline': sidebarOpen}">Profile</span>
            </x-sidebar-nav-link> --}}
            @endauth

            <!-- Post Button -->
            <div class="pt-4" :class="sidebarOpen ? '' : 'px-0.5'">
                <a href="{{ route('posts.create') }}"
                   class="flex items-center justify-center w-full font-bold text-white transition-colors rounded-full shadow-lg bg-primary-DEFAULT hover:bg-primary-dark hover:shadow-primary-DEFAULT/50"
                   :class="sidebarOpen ? 'py-3 px-4' : 'h-12 w-12'">
                    <i class="text-lg fas fa-feather-alt fa-fw" :class="sidebarOpen ? 'lg:mr-2 hidden lg:inline' : 'hidden'"></i>
                    <span x-show="sidebarOpen" :class="{'hidden lg:inline': sidebarOpen}">Post</span>
                    <i class="text-lg fas fa-plus fa-fw" :class="sidebarOpen ? 'hidden' : 'inline'"></i>
                </a>
            </div>
        </nav>
    </div>

    <!-- User Profile Section at Bottom & Sidebar Toggle -->
    <div class="p-2 border-t border-gray-200 dark:border-gray-800 " :class="sidebarOpen ? 'lg:p-4' : ''">
         <!-- Sidebar Toggle -->
        <button @click="sidebarOpen = !sidebarOpen; $dispatch('sidebar-toggled', sidebarOpen)"
                 class="flex items-center justify-center w-full p-2 mb-2 text-gray-500 rounded-full dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none">
            <i class="fas" :class="sidebarOpen ? 'fa-angle-double-left' : 'fa-angle-double-right'"></i>
            <span class="sr-only">Toggle sidebar</span>
        </button>
        @auth
        <a href="{{ route('profile.show', Auth::user()->username) }}"
           class="flex items-center p-2 space-x-3 rounded-full cursor-pointer group hover:bg-gray-100 dark:hover:bg-gray-800"
           :class="!sidebarOpen ? 'justify-center' : ''">
            <img class="flex-shrink-0 object-cover w-10 h-10 rounded-full" src="{{ Auth::user()->avatarUrl() }}" alt="{{ Auth::user()->name }}">
            <div x-show="sidebarOpen" :class="{'hidden lg:block': sidebarOpen}">
                <p class="text-sm font-semibold text-gray-800 truncate dark:text-gray-100">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 truncate dark:text-gray-400">{{ '@'.Auth::user()->username }}</p>
            </div>
        </a>
        @else
         <div class="flex flex-col space-y-1" :class="!sidebarOpen ? 'items-center' : ''">
            <a href="{{ route('login') }}" class="w-full py-2 text-sm font-semibold text-center rounded-full bg-primary-DEFAULT/10 text-primary-DEFAULT dark:text-primary-light" :class="sidebarOpen ? 'px-4' : 'h-10 w-10 flex items-center justify-center'">
                <i class="fas fa-sign-in-alt" :class="sidebarOpen ? 'mr-2' : ''"></i> <span x-show="sidebarOpen">Login</span>
            </a>
         </div>
        @endauth
    </div>
</aside>
