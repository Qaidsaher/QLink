<div class="bg-white/80 dark:bg-black/80 backdrop-blur-sm" x-data="{}" x-init="
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);


        const animateOnScroll = (selector, stagger = 0) => {
            gsap.from(selector, {
                scrollTrigger: {
                    trigger: selector,
                    start: 'top 85%', // Trigger when the top of the element is 85% from the top of the viewport
                    toggleActions: 'play none none none', // Play the animation once and don't reset
                },
                opacity: 0,
                y: 40,         // Animate from 40px below
                duration: 0.8,
                ease: 'power3.out',
                stagger: stagger // Stagger amount for multiple items
            });
        };

        // --- APPLYING ANIMATIONS TO SECTIONS ---

        // 1. Animate the main platform header
        animateOnScroll('.anim-group-1');
        
        // 2. Animate the 'Core Philosophy' cards with a stagger effect
        animateOnScroll('.anim-group-2', 0.15);

        // 3. Animate the 'Anatomy of the Application' section
        animateOnScroll('.anim-group-3');

        // 4. Animate the 'Data Architecture' models
        animateOnScroll('.anim-group-4', 0.1);
        
        // 5. Animate the 'Meet the Developer' header
        animateOnScroll('.anim-group-5');

        // 6. Animate the developer bio card
        animateOnScroll('.anim-group-6');

        // 7. Animate the 'Core Competencies' section
        animateOnScroll('.anim-group-7', 0.15);
        
        // 8. Animate the 'Project Showcase' cards
        animateOnScroll('.anim-group-8', 0.15);
    }
">


    {{-- Sticky Header --}}
    <header
        class="sticky top-0 z-30 transition-colors duration-300 border-b bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border-slate-200/80 dark:border-slate-800/80">
        <div class="flex items-center gap-2 px-2 py-3 ">
            <a href="{{ url()->previous(route('feed')) }}" wire:navigate
                class="p-2 transition-all duration-200 rounded-full text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">About
                {{ config('app.name') }}
            </h1>
        </div>
    </header>

    {{-- Main Content Container --}}
    <main class="relative z-10 p-4 py-8 space-y-16 sm:p-6 lg:p-8 sm:py-12" x-ref="mainContent">

        <!-- ======================= SECTION 1: ABOUT THE PLATFORM ======================= -->
        <section aria-labelledby="about-platform-heading" class="max-w-5xl mx-auto">
            <!-- Section Header -->
            <div class="text-center anim-group-1">
                <div class="flex justify-center">

                    <img src="{{ asset('images/logo1.svg') }}" class="object-fill w-18 h-18" />

                </div>
                <h2 id="about-platform-heading" class="mt-4 text-3xl font-extrabold text-gray-900 dark:text-white">
                    {{ config('app.name', 'QLink') }}
                </h2>
                <p class="font-semibold text-blue-600 dark:text-blue-400">The Vision & Technology</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tighter text-slate-900 dark:text-white ">
                    Crafting a Modern Social Experience
                </h2>
                <p class="max-w-3xl mx-auto mt-4 text-lg text-slate-600 dark:text-slate-400">
                    {{ config('app.name', 'QLink') }} is more than just a project; it's a deep-dive into the art and
                    science of building a high-performance, real-time social media application. It serves as a living
                    portfolio, demonstrating advanced web development techniques, architectural patterns, and a
                    relentless focus on user experience, all powered by the elegant TALL stack.
                </p>
            </div>


            <!-- Core Philosophy Cards -->
            <div class="grid grid-cols-1 gap-6 mt-12 ">
                {{-- Added 'anim-group-2' class to each card for staggered animation --}}
                <div
                    class="p-6 transition-all duration-300 border shadow-lg anim-group-2 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-xl dark:bg-blue-900/50">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.037-.502.068-.75.097h-1.5c-.325 0-.642.06- .943.175L3.3 5.511c-.653.38-1.223.94-1.636 1.591a2.25 2.25 0 00-.659 1.591v5.714c0 .622.223 1.203.659 1.591l2.458 1.418c.3.175.618.268.943.268h1.5c.248-.029.499-.06.75-.097v-5.714c0-.622-.223-1.203-.659-1.591L5 8.5" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.25 3.104v5.714a2.25 2.25 0 00.659 1.591l4.441 2.564M14.25 3.104c.251.037.502.068.75.097h1.5c.325 0 .642.06.943.175l2.458 1.418c.653.38 1.223.94 1.636 1.591a2.25 2.25 0 01.659 1.591v5.714c0 .622-.223 1.203-.659 1.591l-2.458 1.418c-.3.175-.618.268-.943.268h-1.5c-.248-.029-.499-.06-.75-.097v-5.714c0-.622.223-1.203.659-1.591L19 8.5" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900 dark:text-white">Performance First</h3>
                    <p class="mt-1 text-slate-600 dark:text-slate-400">Leveraging Livewire's minimal payload and lazy
                        loading to deliver a snappy, responsive feel. Database queries are meticulously optimized and
                        indexed for speed.</p>
                </div>
                <div
                    class="p-6 transition-all duration-300 border shadow-lg anim-group-2 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl hover:shadow-xl hover:-translate-y-1">
                    <div
                        class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-xl dark:bg-green-900/50">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900 dark:text-white">Modular Architecture</h3>
                    <p class="mt-1 text-slate-600 dark:text-slate-400">Built with a component-driven mindset. Each
                        feature is a self-contained Livewire component, ensuring code is reusable, testable, and easy to
                        maintain or upgrade.</p>
                </div>
                <div
                    class="p-6 transition-all duration-300 border shadow-lg anim-group-2 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-center w-12 h-12 bg-sky-100 rounded-xl dark:bg-sky-900/50">
                        <svg class="w-6 h-6 text-sky-600 dark:text-sky-300" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-1.621-.871A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900 dark:text-white">Developer Experience</h3>
                    <p class="mt-1 text-slate-600 dark:text-slate-400">The TALL stack provides an incredible DX. Rapid
                        prototyping with Livewire, utility-first styling with Tailwind, and the power of Laravel's
                        ecosystem make development a joy.</p>
                </div>
            </div>

            <!-- Technology Stack -->
            <div class="pt-16 mt-16 border-t border-slate-200 dark:border-slate-700/60">
                <div class="anim-group-3">
                    <h3 class="text-2xl font-bold text-center text-slate-900 dark:text-white">The Anatomy of the
                        Application</h3>
                    <p class="max-w-2xl mx-auto mt-2 text-center text-slate-600 dark:text-slate-400">Every piece of
                        technology was carefully chosen for its strengths in creating modern, dynamic, and scalable web
                        applications.</p>
                </div>
                <div class="grid grid-cols-1 gap-8 mt-10 md:grid-cols-2 ">
                    {{-- Added 'anim-group-3' class to each card for staggered animation --}}
                    <div
                        class="p-6 text-center border anim-group-3 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl">
                        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logo-lockup-cmyk-red.svg"
                            alt="Laravel Logo" class="h-12 mx-auto">
                        <h4 class="mt-4 text-lg font-semibold text-slate-800 dark:text-white">Laravel 12</h4>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">The robust PHP framework provides the
                            foundation, handling routing, authentication, data modeling with Eloquent, and the core
                            application logic with unparalleled elegance.</p>
                    </div>
                    <div
                        class="p-6 text-center border anim-group-3 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl">
                        <img src="https://livewire.laravel.com/img/logo.svg" alt="Livewire Logo" class="h-12 mx-auto">
                        <h4 class="mt-4 text-lg font-semibold text-slate-800 dark:text-white">Livewire 3</h4>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">The magic behind the real-time,
                            dynamic interface. Livewire allows for building complex, interactive components using PHP,
                            eliminating the need for a separate JS framework.</p>
                    </div>
                    <div
                        class="p-6 text-center border anim-group-3 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl">
                        <img src="https://alpinejs.dev/logo.svg" alt="Alpine.js Logo" class="h-12 mx-auto dark:invert">
                        <h4 class="mt-4 text-lg font-semibold text-slate-800 dark:text-white">Alpine.js</h4>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">The perfect companion to Livewire for
                            client-side interactivity. Used for lightweight DOM manipulations, transitions, and managing
                            small component states.</p>
                    </div>
                    <div
                        class="p-6 text-center border anim-group-3 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl">
                        <img src="https://tailwindcss.com/_next/static/media/tailwindcss-mark.3c5441fc7a190fb1800d4a5c7f07ba4b.svg"
                            alt="Tailwind CSS Logo" class="h-12 mx-auto">
                        <h4 class="mt-4 text-lg font-semibold text-slate-800 dark:text-white">Tailwind CSS</h4>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">A utility-first CSS framework that
                            enables rapid UI development directly in the markup, resulting in highly customized and
                            responsive designs without writing custom CSS.</p>
                    </div>
                    <div
                        class="p-6 text-center border anim-group-3 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl">
                        <svg class="h-12 mx-auto" viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M85.4,69.1c-3.1-1.3-6.5-2.2-10.1-2.2c-4.4,0-8.4,1.2-11.8,3.4c-2.7,1.7-5.1,3.9-6.9,6.5c-2.3,3.3-3.4,7.2-3.4,11.5   c0,4.2,1.2,8.2,3.4,11.5c1.8,2.6,4.2,4.8,6.9,6.5c3.4,2.2,7.4,3.4,11.8,3.4c5,0,9.6-1.7,13.4-4.9c3.8-3.2,6.4-7.7,6.4-12.7   c0-2.3-0.5-4.6-1.4-6.8c-0.5-1.1-1.1-2.2-1.8-3.3c-0.7-1-1.5-2-2.3-2.9c-0.1,0-0.1,0-0.2-0.1c-0.4-0.5-0.9-0.9-1.3-1.4   c0.5-0.1,1-0.2,1.5-0.3c3.8-0.9,7.2-2.9,9.8-5.6c2.7-2.7,4.3-6.3,4.3-10.3c0-4-1.6-7.6-4.3-10.3c-2.7-2.7-6.3-4.3-10.3-4.3   c-4.9,0-9.2,1.9-12.3,5.4l0,0c-0.6,0.7-1.2,1.4-1.7,2.2L64,66.8l-4.5-6.7c-0.5-0.8-1.1-1.5-1.7-2.2c-3.1-3.5-7.4-5.4-12.3-5.4   c-4,0-7.6,1.6-10.3,4.3c-2.7,2.7-4.3,6.3-4.3,10.3c0,4,1.6,7.6,4.3,10.3c2.7,2.7,6.2,4.7,9.8,5.6c0.5,0.1,1,0.2,1.5,0.3   c-0.5,0.5-0.9,0.9-1.3,1.4c-0.1,0.1-0.1,0.1-0.2,0.1c-0.8,0.9-1.6,1.9-2.3,2.9c-0.7,1.1-1.3,2.2-1.8,3.3c-0.9,2.2-1.4,4.5-1.4,6.8   c0,5,2.6,9.5,6.4,12.7c3.8,3.2,8.4,4.9,13.4,4.9c4.4,0,8.4-1.2,11.8-3.4c2.7-1.7,5.1-3.9,6.9-6.5c2.3-3.3,3.4-7.2,3.4-11.5   c0-4.2-1.2-8.2-3.4-11.5c-1.8-2.6-4.2-4.8-6.9-6.5C72.8,68,68.8,66.8,64.4,66.8c-3.6,0-7,0.9-10.1,2.2c-0.9,0.4-1.7,0.8-2.5,1.3   c-2.3,1.5-4.2,3.4-5.7,5.7c-1.5,2.3-2.3,4.9-2.3,7.7c0,2.8,0.8,5.4,2.3,7.7c1.5,2.3,3.4,4.2,5.7,5.7c2.3,1.5,4.9,2.3,7.7,2.3   c2.8,0,5.4-0.8,7.7-2.3c2.3-1.5,4.2-3.4,5.7-5.7c0.8-1.2,1.4-2.5,1.9-3.9c0.1-0.4,0.3-0.8,0.4-1.2c0.1-0.4,0.2-0.7,0.2-1.1v-1.6   c0-0.4-0.1-0.8-0.2-1.1c-0.1-0.4-0.2-0.8-0.4-1.2c-0.5-1.4-1.1-2.7-1.9-3.9c-1.5-2.3-3.4-4.2-5.7-5.7c-2.3-1.5-4.9-2.3-7.7-2.3   c-2.8,0-5.4,0.8-7.7,2.3c-2.3,1.5-4.2,3.4-5.7,5.7c-1.5,2.3-2.3,4.9-2.3,7.7c0,1.3,0.2,2.6,0.6,3.8c0.8,2.4,2.2,4.5,4.1,6.2   c1.9,1.7,4.2,2.8,6.8,3.2v-9.3c0-3.3,2.7-6,6-6s6,2.7,6,6v9.3c2.6-0.4,4.9-1.5,6.8-3.2c1.9-1.7,3.3-3.8,4.1-6.2   c0.4-1.2,0.6-2.5,0.6-3.8c0-2.8-0.8-5.4-2.3-7.7c-1.5-2.3-3.4-4.2-5.7-5.7c-2.3-1.5-4.9-2.3-7.7-2.3s-5.4,0.8-7.7,2.3   c-2.3,1.5-4.2,3.4-5.7,5.7c-1.5,2.3-2.3,4.9-2.3,7.7c0,2.8,0.8,5.4,2.3,7.7c1.5,2.3,3.4,4.2,5.7,5.7c0.8,0.5,1.6,0.9,2.5,1.3z"
                                fill="#00758f" />
                            <path
                                d="M11,104.4c2.6-0.4,4.9-1.5,6.8-3.2c1.9-1.7,3.3-3.8,4.1-6.2c0.4-1.2,0.6-2.5,0.6-3.8c0-2.8-0.8-5.4-2.3-7.7   c-1.5-2.3-3.4-4.2-5.7-5.7C22.2,76.3,19.6,77,17,77c-2.8,0-5.4-0.8-7.7-2.3c-2.3-1.5-4.2-3.4-5.7-5.7C2,66.7,1.2,64.1,1.2,61.3   c0-2.8,0.8-5.4,2.3-7.7c1.5-2.3,3.4-4.2,5.7-5.7C11.5,46.4,14.1,47,17,47c2.8,0,5.4-0.8,7.7-2.3c2.3-1.5,4.2-3.4,5.7-5.7   c1.5-2.3,2.3-4.9,2.3-7.7c0-2.8-0.8-5.4-2.3-7.7c-1.5-2.3-3.4-4.2-5.7-5.7C22.2,16.2,19.6,17,17,17c-4,0-7.6,1.6-10.3,4.3   C4,24,2.4,27.6,2.4,31.6c0,4,1.6,7.6,4.3,10.3c2.7,2.7,6.3,4.3,10.3,4.3c0.4,0,0.8,0,1.2-0.1c-0.1,0.5-0.2,1-0.2,1.5   c0,4,1.6,7.6,4.3,10.3c2.7,2.7,6.3,4.3,10.3,4.3s7.6-1.6,10.3-4.3c2.7-2.7,4.3-6.3,4.3-10.3c0-0.5-0.1-1-0.2-1.5   c0.4,0,0.8,0.1,1.2,0.1c4,0,7.6-1.6,10.3-4.3c2.7-2.7,4.3-6.3,4.3-10.3c0-4-1.6-7.6-4.3-10.3C55.4,21.3,51.8,17,47.8,17   c-2.8,0-5.4,0.8-7.7,2.3c-2.3,1.5-4.2,3.4-5.7,5.7C32.8,27.3,32,29.9,32,32.7c0,2.8,0.8,5.4,2.3,7.7c1.5,2.3,3.4,4.2,5.7,5.7   c2.3,1.5,4.9,2.3,7.7,2.3c2.8,0,5.4-0.8,7.7-2.3c2.3-1.5,4.2-3.4,5.7-5.7c1.5-2.3,2.3-4.9,2.3-7.7c0-2.8-0.8-5.4-2.3-7.7   c-1.5-2.3-3.4-4.2-5.7-5.7c-2.3-1.5-4.9-2.3-7.7-2.3c-4.9,0-9.2,1.9-12.3,5.4c-0.6,0.7-1.2,1.4-1.7,2.2L32,43.5l-4.5-6.7   c-0.5-0.8-1.1-1.5-1.7-2.2C22.7,31.1,18.4,29,13.5,29c-4,0-7.6,1.6-10.3,4.3C0.5,36,0.5,39.7,0.5,43.6c0,4,1.6,7.6,4.3,10.3   c2.7,2.7,6.3,4.3,10.3,4.3c4.9,0,9.2-1.9,12.3-5.4c0.6-0.7,1.2-1.4,1.7-2.2l4.5,6.7c0.5,0.8,1.1,1.5,1.7,2.2   c3.1,3.5,7.4,5.4,12.3,5.4c4,0,7.6-1.6,10.3-4.3c2.7-2.7,4.3-6.3,4.3-10.3c0-4-1.6-7.6-4.3-10.3c-2.7-2.7-6.3-4.3-10.3-4.3   c-0.4,0-0.8,0-1.2,0.1c0.1-0.5,0.2-1,0.2-1.5c0-4-1.6-7.6-4.3-10.3C40.4,26,36.8,24,32.8,24s-7.6,1.6-10.3,4.3   c-2.7,2.7-4.3,6.3-4.3,10.3c0,0.5,0.1,1,0.2,1.5c-0.4,0-0.8-0.1-1.2-0.1c-4,0-7.6,1.6-10.3,4.3C4.4,47.3,2.8,51,2.8,55   c0,4,1.6,7.6,4.3,10.3c2.7,2.7,6.3,4.3,10.3,4.3c2.8,0,5.4-0.8,7.7-2.3c2.3-1.5,4.2-3.4,5.7-5.7c1.5-2.3,2.3-4.9,2.3-7.7   c0-2.8-0.8-5.4-2.3-7.7c-1.5-2.3-3.4-4.2-5.7-5.7c-2.3-1.5-4.9-2.3-7.7-2.3s-5.4,0.8-7.7,2.3c-2.3,1.5-4.2,3.4-5.7,5.7   C2,59.3,1.2,61.9,1.2,64.7c0,2.8,0.8,5.4,2.3,7.7c1.5,2.3,3.4,4.2,5.7,5.7c2.3,1.5,4.9,2.3,7.7,2.3s5.4-0.8,7.7-2.3   c2.3-1.5,4.2-3.4,5.7-5.7c1.5-2.3,2.3-4.9,2.3-7.7v-1.6c0-0.4-0.1-0.8-0.2-1.1c-0.1-0.4-0.2-0.8-0.4-1.2   c-0.5-1.4-1.1-2.7-1.9-3.9c-1.5-2.3-3.4-4.2-5.7-5.7C19.6,50,17,49.2,14.2,49.2c-2.8,0-5.4,0.8-7.7,2.3   c-2.3,1.5-4.2,3.4-5.7,5.7c-1.5,2.3-2.3,4.9-2.3,7.7c0,1.3,0.2,2.6,0.6,3.8c0.8,2.4,2.2,4.5,4.1,6.2C11.1,84.1,11,104.4,11,104.4z"
                                fill="#f29111" />
                            <path
                                d="M125.6,41.3c-2.7-2.7-6.3-4.3-10.3-4.3c-4.9,0-9.2,1.9-12.3,5.4c-0.6,0.7-1.2,1.4-1.7,2.2l-4.5,6.7   c-0.5,0.8-1.1,1.5-1.7,2.2c-3.1,3.5-7.4,5.4-12.3,5.4c-4,0-7.6-1.6-10.3-4.3c-2.7-2.7-4.3-6.3-4.3-10.3c0-4,1.6-7.6,4.3-10.3   c2.7-2.7,6.3-4.3,10.3-4.3c0.4,0,0.8,0,1.2,0.1c-0.1-0.5-0.2-1-0.2-1.5c0-4,1.6-7.6,4.3-10.3c2.7-2.7,6.3-4.3,10.3-4.3   s7.6,1.6,10.3,4.3c2.7,2.7,4.3-6.3,4.3,10.3c0,0.5-0.1,1-0.2,1.5c0.4,0,0.8,0.1,1.2,0.1c4,0,7.6-1.6,10.3-4.3   c2.7-2.7,4.3-6.3,4.3-10.3c0-4-1.6-7.6-4.3-10.3c-2.7-2.7-6.3-4.3-10.3-4.3c-2.8,0-5.4,0.8-7.7,2.3c-2.3,1.5-4.2,3.4-5.7,5.7   c-1.5,2.3-2.3,4.9-2.3,7.7c0,2.8,0.8,5.4,2.3,7.7c1.5,2.3,3.4,4.2,5.7,5.7c2.3,1.5,4.9,2.3,7.7,2.3s5.4-0.8,7.7-2.3   c2.3-1.5,4.2-3.4,5.7-5.7c1.5-2.3,2.3-4.9,2.3-7.7c0-2.8-0.8-5.4-2.3-7.7c-1.5-2.3-3.4-4.2-5.7-5.7c-2.3-1.5-4.9-2.3-7.7-2.3   c-4.9,0-9.2,1.9-12.3,5.4c-0.6,0.7-1.2,1.4-1.7,2.2l-4.5,6.7c-0.5,0.8-1.1-1.5-1.7-2.2C81.3,46.9,77,49,72.1,49   c-4,0-7.6-1.6-10.3-4.3c-2.7-2.7-4.3-6.3-4.3-10.3c0-4,1.6-7.6-4.3-10.3c2.7-2.7,6.3-4.3,10.3-4.3c4.9,0,9.2,1.9,12.3,5.4   c0.6,0.7,1.2,1.4,1.7,2.2l4.5-6.7c0.5-0.8,1.1-1.5,1.7-2.2C99.6,14.9,103.9,13,108.8,13c4,0,7.6,1.6,10.3,4.3   c2.7,2.7,4.3,6.3,4.3,10.3c0,4-1.6,7.6-4.3,10.3c-2.6,2.8-6.1,4.4-10.2,4.4c-0.4,0-0.8,0-1.2-0.1c0.1,0.5,0.2,1,0.2,1.5   c0,4-1.6,7.6-4.3,10.3c-2.7-2.7-6.3-4.3-10.3-4.3s-7.6-1.6-10.3-4.3c-2.7-2.7-4.3-6.3-4.3-10.3c0-0.5,0.1-1,0.2-1.5   c-0.4,0-0.8-0.1-1.2-0.1c-4,0-7.6-1.6-10.3-4.3c-2.7-2.7-4.3-6.3-4.3-10.3c0,4,1.6,7.6,4.3,10.3c2.7,2.7,6.3,4.3,10.3,4.3   c2.8,0,5.4-0.8,7.7-2.3c2.3-1.5,4.2-3.4,5.7-5.7c1.5-2.3,2.3-4.9,2.3-7.7c0-2.8-0.8-5.4-2.3-7.7c-1.5-2.3-3.4-4.2-5.7-5.7   c-2.3-1.5-4.9-2.3-7.7-2.3s-5.4,0.8-7.7,2.3c-2.3,1.5-4.2,3.4-5.7,5.7c-1.5,2.3-2.3,4.9-2.3,7.7c0,2.8,0.8,5.4,2.3,7.7   c1.5,2.3,3.4,4.2,5.7,5.7c2.3,1.5,4.9,2.3,7.7,2.3c2.8,0,5.4-0.8,7.7-2.3c2.3-1.5,4.2-3.4,5.7-5.7c0.8-1.2,1.4-2.5,1.9-3.9   c0.1-0.4,0.3-0.8,0.4-1.2c0.1-0.4,0.2-0.7,0.2-1.1v-1.6c0-0.4-0.1-0.8-0.2-1.1c-0.1-0.4-0.2-0.8-0.4-1.2   c-0.5-1.4-1.1-2.7-1.9-3.9c-1.5-2.3-3.4-4.2-5.7-5.7c-2.3-1.5-4.9-2.3-7.7-2.3c-2.8,0-5.4,0.8-7.7,2.3   c-2.3,1.5-4.2,3.4-5.7,5.7c-1.5,2.3-2.3,4.9-2.3,7.7c0,1.3,0.2,2.6,0.6,3.8c0.8,2.4,2.2,4.5,4.1,6.2c1.9,1.7,4.2,2.8,6.8,3.2v-9.3   c0-3.3,2.7-6,6-6s6,2.7,6,6v9.3c2.6-0.4,4.9-1.5,6.8-3.2c1.9-1.7,3.3-3.8,4.1-6.2c0.4-1.2,0.6-2.5,0.6-3.8   c0-2.8-0.8-5.4-2.3-7.7c-1.5-2.3-3.4-4.2-5.7-5.7c-2.3-1.5-4.9-2.3-7.7-2.3c-2.8,0-5.4,0.8-7.7,2.3   c-2.3,1.5-4.2,3.4-5.7,5.7c-1.5,2.3-2.3,4.9-2.3,7.7c0,2.8,0.8,5.4,2.3,7.7c1.5,2.3,3.4,4.2,5.7,5.7c2.3,1.5,4.9,2.3,7.7,2.3   c4,0,7.6-1.6,10.3-4.3c2.7-2.7,4.3-6.3,4.3-10.3C128,47.6,128.3,44,125.6,41.3z"
                                fill="#00758f" />
                        </svg>
                        <h4 class="mt-4 text-lg font-semibold text-slate-800 dark:text-white">MySQL</h4>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">The world's most popular open-source
                            relational database. It provides a reliable, scalable, and high-performance foundation for
                            storing all application data.</p>
                    </div>
                    <div
                        class="p-6 text-center border anim-group-3 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl md:col-span-2 lg:col-span-1">
                        <svg class="h-12 mx-auto text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.354a15.056 15.056 0 01-4.5 0m3.75-12.011m0 0A23.953 23.953 0 0112 5.25c-2.11 0-4.152.394-6 .982m12 0c.394-.188.78-.394 1.164-.592M18 12a6 6 0 11-12 0c0-1.042.316-2.016.874-2.857M18 12a6 6 0 01-6 6c-1.042 0-2.016-.316-2.857-.874M18 12a6 6 0 01-6-6c1.042 0 2.016.316 2.857.874" />
                        </svg>
                        <h4 class="mt-4 text-lg font-semibold text-slate-800 dark:text-white">GSAP (Animation)</h4>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">The GreenSock Animation Platform is
                            used to add professional, high-performance animations and micro-interactions, enhancing the
                            user experience.</p>
                    </div>
                </div>
            </div>

            <!-- Database Schema & Models -->
            <div class="pt-16 mt-16 border-t border-slate-200 dark:border-slate-700/60">
                <div class="text-center anim-group-4">
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Data Architecture & Models</h3>
                    <p class="max-w-2xl mx-auto mt-2 text-slate-600 dark:text-slate-400">The application's data is
                        structured around a set of core Eloquent models, representing the fundamental entities of a
                        social network. The schema is designed for efficiency, scalability, and relational integrity.
                    </p>
                </div>
                <div class="mt-10 space-y-4">
                    {{-- Added 'anim-group-4' class to each card for staggered animation --}}
                    <div
                        class="p-5 border anim-group-4 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-xl">
                        <h4 class="font-mono font-bold text-blue-600 dark:text-blue-400">User::class</h4>
                        <p class="mt-1 text-slate-600 dark:text-slate-400">The central model for authentication and
                            identity. A User is the nexus of all activity, owning content and forming connections.</p>
                        <p class="mt-2 text-xs font-semibold text-slate-500 dark:text-slate-400">Relationships:
                            `hasMany` (Post, Comment, Like), `belongsToMany` (User via 'followers' table).</p>
                    </div>
                    <div
                        class="p-5 border anim-group-4 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-xl">
                        <h4 class="font-mono font-bold text-blue-600 dark:text-blue-400">Post::class</h4>
                        <p class="mt-1 text-slate-600 dark:text-slate-400">Represents a single content entry in the main
                            feed. This is the primary content entity that users interact with.</p>
                        <p class="mt-2 text-xs font-semibold text-slate-500 dark:text-slate-400">Relationships:
                            `belongsTo` (User), `hasMany` (Comment, Like, Attachment), `morphMany` (Notifications).</p>
                    </div>
                    <div
                        class="p-5 border anim-group-4 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-xl">
                        <h4 class="font-mono font-bold text-blue-600 dark:text-blue-400">Comment::class</h4>
                        <p class="mt-1 text-slate-600 dark:text-slate-400">A reply to a post, forming the basis of
                            conversations. Comments are nested under posts to create discussion threads.</p>
                        <p class="mt-2 text-xs font-semibold text-slate-500 dark:text-slate-400">Relationships:
                            `belongsTo` (User, Post).</p>
                    </div>
                    <div
                        class="p-5 border anim-group-4 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-xl">
                        <h4 class="font-mono font-bold text-blue-600 dark:text-blue-400">Like, Follow, Attachment</h4>
                        <p class="mt-1 text-slate-600 dark:text-slate-400">These models handle specific, discrete
                            actions and associations. `Follow` is a pivot model for the many-to-many user relationship,
                            `Like` tracks engagement, and `Attachment` manages media uploads.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======================= SECTION 2: ABOUT THE DEVELOPER ======================= -->
        <section aria-labelledby="about-developer-heading" class="max-w-5xl mx-auto">
            <div class="pt-16 mt-16 border-t border-slate-200 dark:border-slate-700/60">
                <!-- Section Header -->
                <div class="text-center anim-group-5">
                    <p class="font-semibold text-green-600 dark:text-green-400">The Architect</p>
                    <h2 id="about-developer-heading"
                        class="mt-2 text-4xl font-extrabold tracking-tighter text-slate-900 dark:text-white sm:text-5xl">
                        Meet the Developer
                    </h2>
                    <p class="max-w-3xl mx-auto mt-4 text-lg text-slate-600 dark:text-slate-400">
                        Behind every line of code is a developer passionate about building elegant solutions to complex
                        problems.
                    </p>
                </div>

                <!-- Developer Bio Card -->
                <div class="p-4 mx-auto mt-16 anim-group-6 bg-white/60 dark:bg-slate-900/60 backdrop-blur-md">
                    <!-- Profile Top -->
                    <div class="flex flex-col items-center text-center">
                        <img src="https://avatars.githubusercontent.com/u/92073238?v=4" alt="Saher Qaid"
                            class="w-24 h-24 rounded-full shadow-lg ring-2 ring-white dark:ring-slate-700">
                        <h2 class="mt-4 text-2xl font-extrabold text-slate-900 dark:text-white">Saher Qaid</h2>
                        <p class="font-semibold text-blue-600 dark:text-blue-400">Senior Laravel Developer & Data
                            Scientist</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Ibb, Yemen</p>

                        <!-- Social Links -->
                        <div class="flex flex-wrap justify-center gap-3 mt-4">
                            <a href="mailto:saherqaid2020@gmail.com"
                                class="text-sm font-medium px-4 py-1.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-600 transition">Email</a>
                            <a href="https://linkedin.com/in/saher-qaid-470735261" target="_blank"
                                class="text-sm font-medium px-4 py-1.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-600 transition">LinkedIn</a>
                            <a href="https://github.com/Qaidsaher" target="_blank"
                                class="text-sm font-medium px-4 py-1.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-600 transition">GitHub</a>
                        </div>
                    </div>

                    <!-- Bio Section -->
                    <div class="mt-10 text-slate-700 dark:text-slate-300">
                        <h3 class="mb-3 text-xl font-bold text-center text-slate-800 dark:text-white md:text-left">
                            Biography</h3>
                        <div class="space-y-5 text-[15px] leading-relaxed">
                            <p>
                                I am a highly skilled and results-driven developer with a unique and powerful dual
                                specialization in full-stack web development and data science. With over two years of
                                dedicated professional experience, I have honed my ability to architect, build, and
                                deploy robust, scalable, and secure web and mobile applications.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Skills Showcase -->
                <div class="pt-16 mt-16 border-t border-slate-200 dark:border-slate-700/60">
                    <h3 class="text-2xl font-bold text-center text-slate-900 dark:text-white anim-group-7">Core
                        Competencies & Skills</h3>
                    <div class="grid grid-cols-1 gap-8 mt-10 ">
                        {{-- Added 'anim-group-7' class to each card for staggered animation --}}
                        <div
                            class="p-6 border anim-group-7 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl">
                            <h4 class="font-semibold text-slate-800 dark:text-white">Languages & Frameworks</h4>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span
                                    class="text-red-700 skill-badge bg-red-500/10 dark:bg-red-500/20 dark:text-red-300">PHP</span>
                                <span
                                    class="text-red-700 skill-badge bg-red-500/10 dark:bg-red-500/20 dark:text-red-300">Laravel</span>
                                <span
                                    class="text-yellow-700 skill-badge bg-yellow-500/10 dark:bg-yellow-500/20 dark:text-yellow-300">JavaScript</span>
                                <span
                                    class="skill-badge bg-sky-500/10 text-sky-700 dark:bg-sky-500/20 dark:text-sky-300">Python</span>
                                <span
                                    class="skill-badge bg-sky-500/10 text-sky-700 dark:bg-sky-500/20 dark:text-sky-300">Flutter</span>
                                <span
                                    class="skill-badge bg-sky-500/10 text-sky-700 dark:bg-sky-500/20 dark:text-sky-300">Dart</span>
                            </div>
                        </div>
                        <div
                            class="p-6 border anim-group-7 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl">
                            <h4 class="font-semibold text-slate-800 dark:text-white">Front-End & UI</h4>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span
                                    class="skill-badge bg-cyan-500/10 text-cyan-700 dark:bg-cyan-500/20 dark:text-cyan-300">Livewire</span>
                                <span
                                    class="text-teal-700 skill-badge bg-teal-500/10 dark:bg-teal-500/20 dark:text-teal-300">Tailwind
                                    CSS</span>
                                <span
                                    class="text-green-700 skill-badge bg-green-500/10 dark:bg-green-500/20 dark:text-green-300">Alpine.js</span>
                                <span
                                    class="skill-badge bg-slate-500/10 text-slate-700 dark:bg-slate-500/20 dark:text-slate-300">HTML5
                                    & CSS3</span>
                            </div>
                        </div>
                        <div
                            class="p-6 border anim-group-7 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl">
                            <h4 class="font-semibold text-slate-800 dark:text-white">Data & Databases</h4>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span
                                    class="text-blue-700 skill-badge bg-blue-500/10 dark:bg-blue-500/20 dark:text-blue-300">MySQL</span>
                                <span
                                    class="text-orange-700 skill-badge bg-orange-500/10 dark:bg-orange-500/20 dark:text-orange-300">TensorFlow</span>
                                <span
                                    class="text-indigo-700 skill-badge bg-indigo-500/10 dark:bg-indigo-500/20 dark:text-indigo-300">Scikit-learn</span>
                                <span
                                    class="text-yellow-700 skill-badge bg-yellow-500/10 dark:bg-yellow-500/20 dark:text-yellow-300">Firebase</span>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Project Showcase Grid -->
                <div class="pt-16 mt-16 border-t border-slate-200 dark:border-slate-700/60">
                    <h3 class="text-2xl font-bold text-center text-slate-900 dark:text-white anim-group-8">Project
                        Showcase</h3>
                    <div class="grid grid-cols-1 gap-8 mt-10 ">
                        {{-- Added 'anim-group-8' class to each card for staggered animation --}}
                        <div
                            class="flex flex-col p-6 transition-all duration-300 border shadow-lg anim-group-8 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl hover:shadow-xl hover:-translate-y-1">
                            <h4 class="text-lg font-bold text-slate-800 dark:text-white">Yemeni Music Classification
                                (AI/ML)</h4>
                            <p class="flex-grow mt-2 text-slate-600 dark:text-slate-400">A pioneering AI/ML model that
                                analyzes audio features to classify traditional Yemeni music into distinct genres. This
                                project, published on Kaggle, achieved an outstanding 96.12% accuracy.</p>
                            <div class="flex flex-wrap gap-2 mt-4">
                                <span class="project-tag">Python</span><span class="project-tag">TensorFlow</span><span
                                    class="project-tag">Scikit-learn</span><span class="project-tag">Librosa</span>
                            </div>
                        </div>
                        <div
                            class="flex flex-col p-6 transition-all duration-300 border shadow-lg anim-group-8 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl hover:shadow-xl hover:-translate-y-1">
                            <h4 class="text-lg font-bold text-slate-800 dark:text-white">Laratalk</h4>
                            <p class="flex-grow mt-2 text-slate-600 dark:text-slate-400">A feature-rich, real-time chat
                                application built with Laravel. It features WebSockets for instant messaging, user roles
                                and permissions, group chats, and interactive polls.</p>
                            <div class="flex flex-wrap gap-2 mt-4">
                                <span class="project-tag">Laravel</span><span class="project-tag">Livewire</span><span
                                    class="project-tag">WebSockets</span><span class="project-tag">MySQL</span>
                            </div>
                        </div>
                        <div
                            class="flex flex-col p-6 transition-all duration-300 border shadow-lg anim-group-8 bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg border-slate-200/50 dark:border-slate-700/50 rounded-2xl hover:shadow-xl hover:-translate-y-1">
                            <h4 class="text-lg font-bold text-slate-800 dark:text-white">Dalalik</h4>
                            <p class="flex-grow mt-2 text-slate-600 dark:text-slate-400">An intelligent real estate
                                platform for Yemen, featuring an AI-powered recommendation engine that suggests
                                properties to users based on their behavior and stated preferences.</p>
                            <div class="flex flex-wrap gap-2 mt-4">
                                <span class="project-tag">Laravel</span><span class="project-tag">AI/ML</span><span
                                    class="project-tag">Google Maps API</span><span class="project-tag">Filament</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </main>
</div>