<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark"> {{-- Default dark mode --}}

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Saher Connect') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300..900&family=Inter:wght@100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @livewireStyles
    <style>
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #4a5568;
            border-radius: 3px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #718096;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        [x-cloak] {
            display: none !important;
        }

        /* For Alpine.js */
    </style>
</head>

<body class="overflow-x-hidden font-sans antialiased text-gray-900 bg-gray-100 dark:bg-gray-950 dark:text-gray-100"
    x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val)); if (localStorage.getItem('darkMode') === 'true') document.documentElement.classList.add('dark');">

    <div class="flex min-h-screen mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Left Sidebar -->
        @include('layouts.partials.left-sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 transition-all duration-300 ease-in-out md:ml-20 lg:ml-64 ">
            <div class="max-w-[700px] min-h-screen mx-auto border-gray-200  dark:border-gray-700 ">
                <!-- Page Specific Header (e.g., "Home", "Profile of X") -->
                @if (isset($header))
                    <header
                        class="sticky top-0 z-30 border-b border-gray-200 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md dark:border-gray-800">
                        <div class="px-4 py-4 sm:px-6">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Main Slot for Page Content / Livewire Components -->
                <main class="p-0"> {{-- Remove default padding if Livewire components handle it --}}
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Right Sidebar -->
        @include('layouts.partials.right-sidebar')
    </div>

    <!-- Mobile Bottom Navigation -->
    @include('layouts.partials.mobile-bottom-nav')


    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind("[data-fancybox]", { /* options */ });
    </script>
    @livewireScripts
    <script>
        // Handle dark mode preference on load (alternative to Alpine init for initial class)
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', isDark);
        }
    </script>
</body>

</html>
