{{-- 
    ==================================================================
    FRONT-END: resources/views/livewire/pages/privacy-page.blade.php
    ==================================================================
--}}

<div x-data="{ show: false }" x-init="() => { setTimeout(() => show = true, 50) }">
    <header class="sticky top-0 z-20 bg-white/80 dark:bg-black/80 backdrop-blur-sm">
        <div class="flex items-center gap-4 px-2 py-2 border-b border-gray-200 dark:border-slate-700">
            <a href="{{ url()->previous(route('feed')) }}" wire:navigate class="p-2 transition-colors rounded-full hover:bg-gray-200 dark:hover:bg-slate-800">
                <svg class="w-5 h-5 text-gray-800 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Privacy Policy</h1>
        </div>
    </header>

    <main class="p-6 prose dark:prose-invert max-w-none" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <h2>1. Information We Collect</h2>
        <p>We collect information you provide directly to us, such as when you create an account, post content, or communicate with us. This may include your name, username, email address, password, and any other information you choose to provide.</p>

        <h2>2. How We Use Your Information</h2>
        <p>We use the information we collect to provide, maintain, and improve our services, including to:</p>
        <ul>
            <li>Authenticate users and provide access to the platform.</li>
            <li>Respond to your comments, questions, and requests.</li>
            <li>Monitor and analyze trends, usage, and activities in connection with our services.</li>
        </ul>

        <h2>3. Sharing of Information</h2>
        <p>We do not share your personal information with third parties except to comply with laws or to protect the rights and property of {{ config('app.name') }}, our users, and others.</p>
        
        <h2>4. Data Security</h2>
        <p>We take reasonable measures to help protect information about you from loss, theft, misuse, and unauthorized access, disclosure, alteration, and destruction.</p>
        
        <h2>5. Contact Us</h2>
        <p>If you have any questions about this Privacy Policy, please contact the developer, Saher Qaid, at <a href="mailto:saherqaid2020@gmail.com">saherqaid2020@gmail.com</a>.</p>

        <p><em>Last updated: {{ now()->format('F j, Y') }}</em></p>
    </main>
</div>
