<script>
    /**
     * Modern Toast Notification System using Tailwind CSS
     * @param {object} options - The options for the toast.
     * @param {string} [options.type='info'] - 'success', 'error', 'info', 'warning'.
     * @param {string} [options.title=''] - The title of the toast.
     * @param {string} [options.message=''] - The main message content.
     * @param {string} [options.position='top-right'] - 'top-right', 'top-left', 'bottom-right', 'bottom-left'.
     * @param {number} [options.duration=8000] - Duration in milliseconds.
     */
    // This JavaScript code is already correct and does not need changes.
    // The fix is adding the HTML comment to your main file.

    function showToast({
        type = 'info',
        title = '',
        message = '',
        position = 'top-right',
        duration = 8000
    }) {

        // 1. Define SVG icons and color classes for each type
        const icons = {
            success: `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
            error: `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
            info: `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
            warning: `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`,
        };
        const typeClasses = {
            success: { accent: 'bg-green-500', icon: 'text-green-500' },
            error: { accent: 'bg-red-500', icon: 'text-red-500' },
            info: { accent: 'bg-sky-500', icon: 'text-sky-500' },
            warning: { accent: 'bg-amber-500', icon: 'text-amber-500' },
        };

        // 2. Get or create the main container
        const getContainer = () => {
            const positionClasses = {
                'top-right': 'top-4 right-4',
                'top-left': 'top-4 left-4',
                'bottom-right': 'bottom-4 right-4',
                'bottom-left': 'bottom-4 left-4',
            };
            const containerId = `toast-container-${position}`;
            let container = document.getElementById(containerId);
            if (!container) {
                container = document.createElement('div');
                container.id = containerId;
                container.className = `fixed z-50 flex w-full max-w-sm flex-col gap-3 ${positionClasses[position]}`;
                document.body.appendChild(container);
            }
            return container;
        };

        const container = getContainer();
        const toastElement = document.createElement('div');

        // 3. Set up the toast's HTML structure
        toastElement.className = 'toast relative w-full transform transition-all duration-300 ease-in-out';
        toastElement.style.setProperty('--toast-accent-color', `var(--tw-${typeClasses[type].accent})`);

        toastElement.innerHTML = `
        <div class="flex items-start gap-4 p-4 pl-6 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-slate-800 dark:ring-white dark:ring-opacity-10">
            <div class="flex-shrink-0 ${typeClasses[type].icon}">${icons[type]}</div>
            <div class="flex-1">
                ${title ? `<p class="font-medium text-slate-900 dark:text-slate-50">${title}</p>` : ''}
                ${message ? `<p class="mt-1 text-sm text-slate-500 dark:text-slate-400">${message}</p>` : ''}
            </div>
            <div class="flex flex-shrink-0">
                <button class="inline-flex p-1 -m-1 rounded-md toast-close text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:hover:bg-slate-700">
                    <span class="sr-only">Dismiss</span>
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                </button>
            </div>
        </div>
        <div class="toast-progress absolute bottom-0 left-0 h-1 bg-fuchsia-600  ${typeClasses[type].accent}" style="animation: toast-progress ${duration}ms linear forwards;"></div>
    `;

        // 4. Handle animations and removal
        const isLeft = position.includes('left');
        toastElement.classList.add('opacity-0', isLeft ? '-translate-x-full' : 'translate-x-full');
        container.appendChild(toastElement);

        requestAnimationFrame(() => {
            toastElement.classList.remove('opacity-0', isLeft ? '-translate-x-full' : 'translate-x-full');
            toastElement.classList.add('opacity-100', 'translate-x-0');
        });

        const removeToast = () => {
            toastElement.classList.add('opacity-0', 'scale-90');
            toastElement.addEventListener('transitionend', () => {
                toastElement.remove();
                if (container.children.length === 0) {
                    container.remove();
                }
            }, { once: true });
        };

        let autoDismissTimeout = setTimeout(removeToast, duration);

        // 5. Add event listeners
        toastElement.querySelector('.toast-close').addEventListener('click', () => {
            clearTimeout(autoDismissTimeout);
            removeToast();
        });

        const progress = toastElement.querySelector('.toast-progress');
        toastElement.addEventListener('mouseenter', () => {
            clearTimeout(autoDismissTimeout);
            progress.style.animationPlayState = 'paused';
        });

        toastElement.addEventListener('mouseleave', () => {
            const remainingDuration = (progress.offsetWidth / toastElement.offsetWidth) * duration;
            progress.style.animationPlayState = 'running';
            autoDismissTimeout = setTimeout(removeToast, remainingDuration);
        });
    }
    // --- DEMO HELPER FUNCTIONS ---
    function showDemoToast(type) {
        const position = document.getElementById('position-select').value;
        const demoMessages = {
            success: { title: 'Success!', message: 'Your changes have been saved successfully.' },
            error: { title: 'Error!', message: 'Could not connect to the server. Please try again.' },
            info: { title: 'Did you know?', message: 'You can customize the position of these notifications.' },
            warning: { title: 'Warning', message: 'Your session is about to expire in 5 minutes.' },
        };
        const { title, message } = demoMessages[type];

        showToast({ type, title, message, position });
    }

    function toggleDarkMode() {
        document.documentElement.classList.toggle('dark');
    }

    // Show an initial welcome toast when the page loads
    document.addEventListener('DOMContentLoaded', () => {
        showToast({
            title: 'Welcome!',
            message: 'This is a Tailwind CSS powered toast notification system.',
            position: 'top-right',
            type: 'info'
        });
    });
</script>