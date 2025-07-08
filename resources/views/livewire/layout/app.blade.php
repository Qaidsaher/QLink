<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? config('app.name', 'QLink') }} - The Social Connection Platform</title>

    <meta name="description"
        content="QLink is a social media platform to connect with friends, share updates, and discover what's trending." />
    <meta name="keywords" content="social media, social network, friends, community, sharing, updates, QLink" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta name="google-site-verification" content="kmU_E-myBUHqdfBG4MLPNJvTycdy3pxnQPT6Xq4hTP8" />
    <meta property="og:title" content="{{ $title ?? config('app.name', 'QLink') }}" />
    <meta property="og:description"
        content="QLink is a social media platform to connect with friends, share updates, and discover what's trending." />
    <meta property="og:image" content="{{ asset('images/qlink-og-image.png') }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="QLink" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $title ?? config('app.name', 'QLink') }}" />
    <meta name="twitter:description"
        content="QLink is a social media platform to connect with friends, share updates, and discover what's trending." />
    <meta name="twitter:image" content="{{ asset('images/qlink-twitter-card-image.png') }}" />
    <meta name="twitter:site" content="@QLinkApp" />
    <meta name="twitter:creator" content="@YourTwitterHandle" />

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "Corporation",
          "name": "QLink",
          "url": "{{ config('app.url') }}",
          "logo": "{{ asset('images/logo1.svg') }}",
          "description": "QLink is a social media platform for connecting people.",
          "sameAs": [
            "https://www.facebook.com/saherqaid",
            "https://twitter.com/saherqaid",
            "https://www.instagram.com/saherqaid"
          ]
        },
        {
          "@type": "WebSite",
          "url": "{{ config('app.url') }}",
          "name": "QLink",
          "publisher": {
            "@type": "Corporation",
            "name": "QLink"
          },
          "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ config('app.url') }}/search?q={search_term_string}",
            "query-input": "required name=search_term_string"
          }
        }
      ]
    }
    </script>


    <!-- Fonts, Icons & Styles -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        xintegrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script>
        function updateSidebarBadges(unreadMessages = 0, unreadNotifications = 0) {
            const msgBadge = document.getElementById('messageBadge');
            const notiBadge = document.getElementById('notificationBadge');

            if (msgBadge) {
                msgBadge.textContent = unreadMessages;
                msgBadge.classList.toggle('hidden', unreadMessages === 0);
            }

            if (notiBadge) {
                notiBadge.textContent = unreadNotifications;
                notiBadge.classList.toggle('hidden', unreadNotifications === 0);
            }
        }

        // Example manual call:
        // updateSidebarBadges(2, 5);

        // // Livewire hook
        // Livewire.on('updateUnreadCounts', ({ messages, notifications }) => {
        //     updateSidebarBadges(messages, notifications);
        // });
    </script>

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
        function initTheme() {

            const stored = localStorage.getItem('theme') || 'dark';
            setTheme(stored);

        }

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

                try {
                    if (typeof Echo === 'undefined' || typeof Echo.join !== 'function') {
                        console.warn('[Echo] Echo is not available. Skipping real-time presence.');
                        return;
                    }

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
                } catch (err) {
                    console.error('[Echo] Failed to initialize:', err);
                }
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
            initTheme();
            startOnlineStatusChecker();
            // updateSidebarBadges(2, 5);
        });

        // Livewire DOM update hooks
        document.addEventListener("livewire:update", () => {
            window.EchoPresenceManager.refresh();
            initTheme();
        });
        document.addEventListener("livewire:navigated", () => {
            window.EchoPresenceManager.refresh();
            initTheme();
        });
        function startOnlineStatusChecker(intervalMs = 1000, timeoutMs = 5 * 60 * 1000) {
            function checkOnlineStatuses() {
                const stored = localStorage.getItem('onlineUsersMap');
                if (!stored) return;

                let parsed;
                try {
                    parsed = JSON.parse(stored);
                } catch (e) {
                    return;
                }

                const now = Date.now();

                // Loop over all DOM elements with data-user-id
                document.querySelectorAll('[data-user-id]').forEach(el => {
                    const userId = el.getAttribute('data-user-id');
                    const lastSeen = parsed[userId];

                    const online = lastSeen && now - Number(lastSeen) <= timeoutMs;

                    const onlineDot = el.querySelector('.online-dot');
                    const offlineDot = el.querySelector('.offline-dot');

                    if (online) {
                        onlineDot?.classList.remove('hidden');
                        offlineDot?.classList.add('hidden');
                    } else {
                        onlineDot?.classList.add('hidden');
                        offlineDot?.classList.remove('hidden');
                    }
                });
            }

            // Initial run
            checkOnlineStatuses();

            // ✅ Only check when tab is visible
            setInterval(() => {
                if (document.visibilityState === 'visible') {
                    checkOnlineStatuses();
                }
            }, intervalMs);
        }

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
    <!-- Trigger Button -->
    <div id="notificationArea" class="fixed z-50 transform -translate-x-1/2 top-10 left-1/2"></div>
    <div id="messageNotificationArea" class="fixed z-50 bottom-5 right-5"></div>


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
    <x-chat-alert />
    {{-- @livewireScripts --}}

</body>

</html>