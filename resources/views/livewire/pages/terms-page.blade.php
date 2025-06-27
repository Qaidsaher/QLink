
{{-- 
    ==================================================================
    FRONT-END: resources/views/livewire/pages/terms-page.blade.php
    ==================================================================
--}}

<div x-data="{ show: false }" x-init="() => { setTimeout(() => show = true, 50) }">
    <header class="sticky top-0 z-20 bg-white/80 dark:bg-black/80 backdrop-blur-sm">
        <div class="flex items-center gap-4 px-2 py-2 border-b border-gray-200 dark:border-slate-700">
            <a href="{{ url()->previous(route('feed')) }}" wire:navigate class="p-2 transition-colors rounded-full hover:bg-gray-200 dark:hover:bg-slate-800">
                <svg class="w-5 h-5 text-gray-800 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Terms of Service</h1>
        </div>
    </header>

    <main class="p-6 prose dark:prose-invert max-w-none" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <h2>1. Introduction</h2>
        <p>Welcome to {{ config('app.name') }}. By accessing or using our service, you agree to be bound by these terms. If you disagree with any part of the terms, you may not access the service.</p>
        
        <h2>2. User Accounts</h2>
        <p>You are responsible for safeguarding your account password. You agree not to disclose your password to any third party and must notify us immediately upon becoming aware of any breach of security or unauthorized use of your account.</p>

        <h2>3. Content</h2>
        <p>Our Service allows you to post content. You are responsible for the content that you post, including its legality, reliability, and appropriateness. By posting content, you grant us the right and license to use, modify, publicly perform, publicly display, reproduce, and distribute such content on and through the Service.</p>

        <h2>4. Termination</h2>
        <p>We may terminate or suspend your account immediately, without prior notice or liability, for any reason, including without limitation if you breach the Terms.</p>
        
        <p><em>Last updated: {{ now()->format('F j, Y') }}</em></p>
    </main>
</div>
