<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{-- x-data="{ dark: @js(session('theme') === 'dark') }"
    x-init="$watch('dark', val => {
          document.documentElement.classList.toggle('dark', val);
          Livewire.dispatch('theme-updated', { dark: val });
      })" :class="{ 'dark': dark }" --}}>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Saher Connect') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        xintegrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Modern, subtle scrollbar for a cleaner look */
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

        @media (max-width: 639px) {
            .custom-small-screen-height {
                height: calc(100vh - 56px);
                min-height: unset;
                /* override min-h-screen */
            }
        }
    </style>
    {{--
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const handledUpdates = new Set(); // Track by post+action+timestamp

            window.Echo.channel('public-posts')
                .listen('.post.updated', (e) => {
                    const key = `${e.id}-${e.action}-${e.updated_at}`;

                    if (handledUpdates.has(key)) {
                        // Already handled this exact update
                        return;
                    }

                    handledUpdates.add(key); // Mark this update as handled

                    console.log(`Handling ${e.action} for post ID ${e.id} at ${e.updated_at}`);

                    // Emit Livewire event
                    switch (e.action) {
                        case 'created':
                            window.Livewire.dispatch('postCreated', { postId: e.id });
                            break;
                        case 'updated':
                            window.Livewire.dispatch('postUpdated', { postId: e.id });
                            break;
                        case 'deleted':
                            window.Livewire.dispatch('postDeleted', { postId: e.id });
                            break;
                        default:
                            console.warn('Unknown action:', e.action);
                            break;
                    }
                });
        });
    </script> --}}


</head>

<body class="font-sans antialiased bg-white text-slate-800 dark:bg-black dark:text-slate-200">

    {{-- Main container uses flex to create the column layout. `justify-center` keeps it centered. --}}
    <div class="flex justify-center min-h-screen">

        <!-- =============================================== -->
        <!-- Left Sidebar (Navigation) -->
        <!-- =============================================== -->

        @include('layouts.partials.left-sidebar')
        <!-- =============================================== -->
        <!-- Main Content (Feed, Profile, etc.) -->
        <!-- =============================================== -->
        <main class="w-full max-w-[600px] min-h-screen border-x border-slate-200 dark:border-slate-800">
            {{ $slot }}
        </main>

        <!-- =============================================== -->
        <!-- Right Sidebar (Search, Trends, etc.) -->
        <!-- =============================================== -->
        @include('layouts.partials.right-sidebar')
        <!-- Mobile Bottom Navigation -->
        @include('layouts.partials.mobile-bottom-nav')
    </div>
    <x-alert />
    <!-- AlpineJS init script -->

</body>

</html>