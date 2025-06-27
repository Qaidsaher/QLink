<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? config('app.name', 'Saher Connect') }}</title>

    <!-- Fonts, Icons & Styles -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        xintegrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <style type="text/tailwindcss">
        @layer utilities {
            /* Animation for the progress bar */
            @keyframes toast-progress {
                from { width: 100%; }
                to { width: 0%; }
            }

            /* Add the accent color bar using a pseudo-element */
            .toast::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 5px;
                height: 100%;
                background-color: var(--toast-accent-color);
            }
        }
    </style> --}}
    {{--
    <script>
        function updateThemeUI(isDark) {
            document.documentElement.classList.toggle('dark', isDark);
            const icon = document.getElementById('theme-icon');
            if (icon) {
                icon.className = isDark ? 'fas fa-moon text-xl' : 'fas fa-sun text-xl';
            }
        }

        function setTheme(theme) {
            localStorage.setItem('theme', theme);
            updateThemeUI(theme === 'dark');
        }

        function toggleTheme() {
            const current = localStorage.getItem('theme') === 'dark' ? 'light' : 'dark';
            setTheme(current);
        }


        function initEchoPresence() {
            Echo.join('online')
                .here(users => {
                    // alert(user)
                    users.forEach(user => setUserOnline(user.id));
                })
                .joining(user => {
                    setUserOnline(user.id);
                })
                .leaving(user => {
                    setUserOffline(user.id);
                });
        }

        function setUserOnline(userId) {
            document.querySelectorAll(`[data-user-id="${userId}"]`).forEach(container => {
                container.querySelector('.online-dot')?.classList.remove('hidden');
                container.querySelector('.offline-dot')?.classList.add('hidden');
            });
        }

        function setUserOffline(userId) {
            document.querySelectorAll(`[data-user-id="${userId}"]`).forEach(container => {
                container.querySelector('.online-dot')?.classList.add('hidden');
                container.querySelector('.offline-dot')?.classList.remove('hidden');
            });
        }

        function initTheme() {
            initEchoPresence();
            const stored = localStorage.getItem('theme') || 'light';
            setTheme(stored);
        }
        document.addEventListener('DOMContentLoaded', () => {
            initTheme();

            // document.getElementById('theme-toggle').addEventListener('click', toggleTheme);

            // Livewire Events
            document.addEventListener('livewire:navigated', initTheme);
            document.addEventListener('livewire:navigate', initTheme);
            document.addEventListener('livewire:navigating', initTheme);
            Livewire.hook('message.processed', initTheme);
            // Init Echo presence tracking once Livewire is ready
            document.addEventListener('livewire:load', () => {

            });

        });
    </script>
    --}}

    <script>
        window.EchoPresenceManager = (() => {
            // Private variables
            let onlineUsersMap = new Map();
            const ONLINE_TIMEOUT = 5 * 60 * 1000; // 5 minutes
            let echoJoined = false;

            // Load online users from localStorage
            function loadOnlineUsers() {
                const stored = localStorage.getItem('onlineUsersMap');
                if (stored) {
                    try {
                        const obj = JSON.parse(stored);
                        onlineUsersMap = new Map(Object.entries(obj));
                        onlineUsersMap.forEach((value, key) => {
                            onlineUsersMap.set(key, Number(value));
                        });
                    } catch {
                        onlineUsersMap = new Map();
                    }
                }
            }

            // Save online users to localStorage
            function saveOnlineUsers() {
                const obj = Object.fromEntries(onlineUsersMap);
                localStorage.setItem('onlineUsersMap', JSON.stringify(obj));
            }

            // Mark user online and update timestamp
            function setUserOnline(userId) {
                onlineUsersMap.set(userId.toString(), Date.now());
                saveOnlineUsers();
                updateUserStatusOnDOM(userId, true);
            }

            // Mark user offline
            function setUserOffline(userId) {
                onlineUsersMap.delete(userId.toString());
                saveOnlineUsers();
                updateUserStatusOnDOM(userId, false);
            }

            // Update avatar dots in the DOM
            function updateUserStatusOnDOM(userId, isOnline) {
                document.querySelectorAll(`[data-user-id="${userId}"]`).forEach(el => {
                    if (isOnline) {
                        el.querySelector('.online-dot')?.classList.remove('hidden');
                        el.querySelector('.offline-dot')?.classList.add('hidden');
                    } else {
                        el.querySelector('.online-dot')?.classList.add('hidden');
                        el.querySelector('.offline-dot')?.classList.remove('hidden');
                    }
                });
            }

            // Remove expired users (not updated within ONLINE_TIMEOUT)
            function purgeExpiredUsers() {
                const now = Date.now();
                let changed = false;
                for (const [userId, lastSeen] of onlineUsersMap.entries()) {
                    if (now - lastSeen > ONLINE_TIMEOUT) {
                        onlineUsersMap.delete(userId);
                        changed = true;
                    }
                }
                if (changed) saveOnlineUsers();
            }

            // Reapply statuses for all tracked online users (used after DOM updates)
            function reapplyAllOnlineStatuses() {
                purgeExpiredUsers();
                onlineUsersMap.forEach((timestamp, userId) => {
                    updateUserStatusOnDOM(userId, true);
                });
            }

            // Initialize Echo presence channel once
            function initEchoPresence() {
                if (echoJoined) return;
                echoJoined = true;

                Echo.join('online')
                    .here(users => {
                        users.forEach(user => setUserOnline(user.id));
                    })
                    .joining(user => {
                        setUserOnline(user.id);
                    })
                    .leaving(user => {
                        setUserOffline(user.id);
                    });
            }

            // Public API
            return {
                init() {
                    loadOnlineUsers();
                    initEchoPresence();
                    reapplyAllOnlineStatuses();

                    // Periodic purge and reapply every minute
                    setInterval(() => {
                        purgeExpiredUsers();
                        reapplyAllOnlineStatuses();
                    }, 60 * 1000);
                },

                refresh() {
                    // Call this after Livewire or DOM updates to fix avatar statuses
                    this.reapplyAllOnlineStatuses();
                },

                reapplyAllOnlineStatuses, // expose if needed externally
            };
        })();

        // Init on DOM load
        document.addEventListener('DOMContentLoaded', () => {
            window.EchoPresenceManager.init();
        });

        // Livewire DOM update hooks
        document.addEventListener("livewire:update", () => {
            window.EchoPresenceManager.refresh();
        });
        document.addEventListener("livewire:navigated", () => {
            window.EchoPresenceManager.refresh();
        });
    </script>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- @livewireStyles --}}

    <style>
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
</head>

<body class="font-sans antialiased bg-white text-slate-800 dark:bg-black dark:text-slate-200">

    <div class="flex justify-center min-h-screen">

        <!-- Left Sidebar (Navigation) -->
        <header
            class="sticky top-0 z-40 flex-col justify-between hidden h-screen border-r md:flex border-slate-200 dark:border-slate-800 md:w-[88px] xl:w-[275px] transition-all duration-300">


            @include('livewire.layout.partials.left-sidebar')
        </header>

        <!-- Main Content (Feed, Profile, etc.) -->
        <main class="w-full max-w-[600px] min-h-screen border-x border-slate-200 dark:border-slate-800">
            {{ $slot }}
        </main>

        <!-- Right Sidebar (Search, Trends, etc.) -->
        <aside class="sticky top-0 flex-col hidden h-screen max-w-[384px] p-4 space-y-4 lg:flex xl:w-96 shrink-0">
            @livewire('global-search')
            @livewire('trending-topics')
            @livewire('suggestions-follow')


            <div class="relative ">
                <button id="theme-toggle" onclick="toggleTheme()"
                    class="flex items-center justify-center w-full gap-2 px-4 py-2 text-sm font-semibold text-blue-900 transition-all duration-200 bg-gray-100 border border-blue-200 rounded-full shadow-sm dark:bg-gray-800 dark:text-yellow-300 dark:border-slate-700 hover:shadow-md">
                    <i id="theme-icon" class="text-lg fas fa-sun"></i>
                    <span id="theme-label">Light Mode</span>
                </button>
            </div>

            <!-- Footer with Theme Toggle -->
            <div class="p-2 mt-auto text-xs text-slate-500">
                <div class="flex items-center justify-center gap-x-4">
                    <a href="{{ route('terms') }}" wire:navigate class="hover:underline">Terms</a>
                    <a href="{{ route('privacy') }}" wire:navigate class="hover:underline">Privacy</a>
                    <a href="{{ route('about') }}" wire:navigate class="hover:underline">About</a>
                </div>
                <p class="mt-2 text-center">© {{ date('Y') }} {{ config('app.name', 'QLink') }} </p>
            </div>
        </aside>

        <!-- Mobile Bottom Navigation -->
        @include('livewire.layout.partials.mobile-bottom-nav')
    </div>
    <x-alert />
    {{-- @livewireScripts --}}
    <script>
        // Self-contained AlpineJS component for theme management
        function theme() {
            return {
                isDarkMode: false,
                init() {
                    // Check for saved preference in localStorage, otherwise use system preference
                    const savedTheme = localStorage.getItem('darkMode');
                    if (savedTheme !== null) {
                        this.isDarkMode = savedTheme === 'true';
                    } else {
                        this.isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    }

                    // Watch for changes to persist them
                    this.$watch('isDarkMode', value => {
                        localStorage.setItem('darkMode', value);
                        if (value) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    });
                },
                toggleTheme() {
                    this.isDarkMode = !this.isDarkMode;
                }
            }
        }
    </script>
</body>

</html>