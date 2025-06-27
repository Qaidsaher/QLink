<nav
    class="fixed bottom-0 left-0 right-0 z-30 flex justify-around border-t h-14 bg-white/80 backdrop-blur-md dark:bg-black/80 border-slate-200 dark:border-slate-800 md:hidden">
    <a href="{{ route('feed') }}" wire:navigate
        class="flex items-center justify-center w-full {{ request()->routeIs('feed') ? 'text-blue-500' : 'text-slate-500' }} hover:text-blue-500"><i
            class="text-2xl fas fa-home"></i></a>
    <a href="{{ route('search.index') }}" wire:navigate
        class="flex items-center justify-center w-full {{ request()->routeIs('search.index') ? 'text-blue-500' : 'text-slate-500' }} hover:text-blue-500"><i
            class="text-2xl fas fa-search"></i></a>
    <a href="{{ route('notifications') }}" wire:navigate
        class="relative flex items-center justify-center w-full {{ request()->routeIs('notifications') ? 'text-blue-500' : 'text-slate-500' }} hover:text-blue-500"><i
            class="text-2xl far fa-bell"></i></a>
    <a href="{{ route('messages') }}" wire:navigate
        class="flex items-center justify-center w-full {{ request()->routeIs('messages') ? 'text-blue-500' : 'text-slate-500' }} hover:text-blue-500"><i
            class="text-2xl far fa-envelope"></i></a>
</nav>