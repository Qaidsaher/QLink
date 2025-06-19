<!-- Right Sidebar (Trends, Suggestions) -->
<aside
    class="sticky top-0 hidden h-screen p-4 space-y-6 overflow-y-auto bg-white border-l border-gray-200 w-80 xl:w-96 dark:bg-gray-900 dark:border-gray-800 lg:block">
    <!-- Search Bar -->
    <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="text-gray-400 fas fa-search"></i>
        </div>
        <input type="search" name="search" id="search-sidebar"
            class="block w-full py-2 pl-10 pr-3 text-sm placeholder-gray-400 border border-gray-300 rounded-full dark:border-gray-700 focus:outline-none focus:ring-1 focus:ring-primary-DEFAULT focus:border-primary-DEFAULT bg-gray-50 dark:bg-gray-800"
            placeholder="Search SaherConnect">
    </div>
    {{-- In layouts/app.blade.php (e.g., in the header) --}}
    @livewire('global-search')


    <!-- Trends for you -->
    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
        <h3 class="mb-3 text-lg font-bold text-gray-900 dark:text-gray-100">Trends for you</h3>
        <ul class="space-y-3">
            <li><a href="#" class="block group">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Trending in YourCountry</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-200 group-hover:underline">#LaravelLivewire</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">15.2K Posts</p>
                </a></li>
            <li><a href="#" class="block group">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Tech · Trending</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-200 group-hover:underline">FlutterDev</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">8,345 Posts</p>
                </a></li>
            <li><a href="#" class="block group">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Gaming · Trending</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-200 group-hover:underline">#NewGameRelease</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">22K Posts</p>
                </a></li>
            <li><a href="#" class="text-sm text-primary-DEFAULT dark:text-primary-light hover:underline">Show more</a>
            </li>
        </ul>
    </div>

    <!-- Who to follow -->
    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
        <h3 class="mb-3 text-lg font-bold text-gray-900 dark:text-gray-100">Who to follow</h3>
        <ul class="space-y-4">
            <li class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img class="object-cover w-10 h-10 rounded-full"
                        src="https://randomuser.me/api/portraits/men/75.jpg" alt="Michael">
                    <div>
                        <a href="#"
                            class="text-sm font-semibold text-gray-800 dark:text-gray-100 hover:underline">Michael
                            G.</a>
                        <p class="text-xs text-gray-500 dark:text-gray-400">@michaelg</p>
                    </div>
                </div>
                <button
                    class="px-3 py-1 text-xs font-semibold border rounded-full text-primary-DEFAULT border-primary-DEFAULT hover:bg-primary-DEFAULT/10">Follow</button>
            </li>
            <li class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img class="object-cover w-10 h-10 rounded-full"
                        src="https://randomuser.me/api/portraits/women/75.jpg" alt="Jessica">
                    <div>
                        <a href="#"
                            class="text-sm font-semibold text-gray-800 dark:text-gray-100 hover:underline">Jessica
                            L.</a>
                        <p class="text-xs text-gray-500 dark:text-gray-400">@jessical</p>
                    </div>
                </div>
                <button
                    class="px-3 py-1 text-xs font-semibold border rounded-full text-primary-DEFAULT border-primary-DEFAULT hover:bg-primary-DEFAULT/10">Follow</button>
            </li>
            <li><a href="#" class="text-sm text-primary-DEFAULT dark:text-primary-light hover:underline">Show more</a>
            </li>
        </ul>
    </div>

    <!-- Footer Links -->
    <footer class="space-x-2 text-xs text-gray-500 dark:text-gray-400">
        <a href="#" class="hover:underline">Terms of Service</a>
        <a href="#" class="hover:underline">Privacy Policy</a>
        <a href="#" class="hover:underline">Cookie Policy</a>
        <span>©
            <script>document.write(new Date().getFullYear())</script> SaherConnect.
        </span>
    </footer>
</aside>








{{--


<aside
    class="top-0 right-0 z-30 hidden h-screen p-6 space-y-6 overflow-y-auto bg-white border-l border-gray-200 w-80 xl:w-96 dark:bg-gray-900 dark:border-gray-800 lg:block">

    <div class="sticky h-screen p-6 fix top-10">
        @auth
            <!-- Search Bar -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="text-gray-400 fas fa-search"></i>
                </div>
                <a href="{{ route('search.page') }}"
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-700 rounded-full text-sm text-gray-400 focus:outline-none focus:ring-1 focus:ring-primary-DEFAULT focus:border-primary-DEFAULT bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700">
                    Search SaherConnect
                </a>
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
                <h3 class="mb-3 text-lg font-bold text-gray-900 dark:text-gray-100">Trends for you</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="block group">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Trending</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200 group-hover:underline">#LivewireMagic
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">1.2K Posts</p>
                        </a></li>
                    <li><a href="#" class="text-sm text-primary-DEFAULT dark:text-primary-light hover:underline">Show
                            more</a></li>
                </ul>
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
                <h3 class="mb-3 text-lg font-bold text-gray-900 dark:text-gray-100">Who to follow</h3>
                <ul class="space-y-4">
                    <li class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <img class="object-cover w-10 h-10 rounded-full"
                                src="https://randomuser.me/api/portraits/men/78.jpg" alt="User">
                            <div>
                                <a href="#"
                                    class="text-sm font-semibold text-gray-800 dark:text-gray-100 hover:underline">Alex
                                    Doe</a>
                                <p class="text-xs text-gray-500 dark:text-gray-400">@alexd</p>
                            </div>
                        </div>
                        <button
                            class="px-3 py-1 text-xs font-semibold border rounded-full text-primary-DEFAULT border-primary-DEFAULT hover:bg-primary-DEFAULT/10">Follow</button>
                    </li>
                </ul>
            </div>
        @endauth

        <button @click="darkMode = !darkMode; toggleDarkMode()"
            class="flex items-center justify-center w-full p-2 mt-4 text-gray-500 rounded-full dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none">
            <i class="mr-2 fas fa-moon dark:hidden"></i>
            <i class="hidden mr-2 fas fa-sun dark:inline"></i>
            <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
        </button>

        <footer class="pt-6 mt-auto space-x-2 text-xs text-center text-gray-500 dark:text-gray-400">
            <a href="#" class="hover:underline">Terms</a>
            <a href="#" class="hover:underline">Privacy</a>
            <span>© {{ date('Y') }} {{ config('app.name') }}.</span>
        </footer>
    </div>

</aside> --}}
