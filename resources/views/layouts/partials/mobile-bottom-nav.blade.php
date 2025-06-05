<nav class="fixed bottom-0 left-0 right-0 z-40 flex items-center justify-around h-16 bg-white border-t border-gray-200 md:hidden dark:bg-gray-800 dark:border-gray-700 shadow-top">
    <a href="{{ route('feed') }}" class="{{ request()->routeIs('feed') ? 'text-primary-DEFAULT' : 'text-gray-500 dark:text-gray-400' }} p-3 flex flex-col items-center hover:text-primary-DEFAULT">
        <i class="fas fa-home fa-lg"></i> <span class="text-[10px] mt-0.5">Home</span>
    </a>
    <a href="{{ route('search.page') }}" class="{{ request()->routeIs('search.page') ? 'text-primary-DEFAULT' : 'text-gray-500 dark:text-gray-400' }} p-3 flex flex-col items-center hover:text-primary-DEFAULT">
        <i class="fas fa-search fa-lg"></i> <span class="text-[10px] mt-0.5">Search</span>
    </a>
    <a href="#" class="relative flex flex-col items-center p-3 text-gray-500 dark:text-gray-400 hover:text-primary-DEFAULT"> {{-- Notifications --}}
        <i class="far fa-bell fa-lg"></i> <span class="text-[10px] mt-0.5">Alerts</span>
        <span class="absolute block w-2 h-2 bg-red-500 rounded-full top-2 right-2 ring-2 ring-white dark:ring-gray-800"></span>
    </a>
    @auth
    <a href="{{ route('profile.show', Auth::user()->username) }}" class="{{ request()->routeIs('profile.show') && request()->route('user') && request()->route('user')->id === Auth::id() ? 'text-primary-DEFAULT' : 'text-gray-500 dark:text-gray-400' }} p-3 flex flex-col items-center hover:text-primary-DEFAULT">
        <i class="far fa-user fa-lg"></i> <span class="text-[10px] mt-0.5">Profile</span>
    </a>
    @else
    <a href="{{ route('login') }}" class="flex flex-col items-center p-3 text-gray-500 dark:text-gray-400 hover:text-primary-DEFAULT">
        <i class="fas fa-sign-in-alt fa-lg"></i> <span class="text-[10px] mt-0.5">Login</span>
    </a>
    @endauth
</nav>q
