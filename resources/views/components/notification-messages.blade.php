<div id="messageNotificationArea" class="fixed z-50 bottom-5 right-5"></div>

<script>
    function notifyNewMessage({ avatarUrl, messagePreview, chatId }, timeoutSeconds = 8) {
        const wrapper = document.getElementById('messageNotificationArea');
        wrapper.innerHTML = ''; // Clear any existing notification

        // Create notification link element
        const container = document.createElement('a');
        container.href = `/chat/${chatId}`; // fallback URL
        container.setAttribute('wire:navigate', '');
        container.setAttribute('class', `
            bg-white dark:bg-gray-800 px-4 py-2 rounded-xl shadow-lg 
            flex items-center gap-4 border border-green-400 dark:border-green-600 
            animate-fade-drop cursor-pointer max-w-sm w-full mb-2
            text-gray-800 dark:text-white no-underline
        `.trim());

        // Avatar
        const avatar = document.createElement('div');
        avatar.className = `
            w-10 h-10 rounded-full overflow-hidden border-2 
            border-white dark:border-gray-700 flex-shrink-0
        `.trim();

        if (avatarUrl) {
            const img = document.createElement('img');
            img.src = avatarUrl;
            img.alt = 'avatar';
            img.className = 'w-full h-full object-cover';
            avatar.appendChild(img);
        } else {
            avatar.textContent = '?'; // fallback text
            avatar.classList.add('bg-green-100', 'flex', 'items-center', 'justify-center');
        }

        // Message Text
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex-1 text-sm';

        const messagePreviewText = messagePreview
            ? messagePreview.split(' ').slice(0, 3).join(' ') + '...'
            : 'New message...';
        messageDiv.innerHTML = `<span class="block font-bold">رسالة جديدة</span><span>${messagePreviewText}</span>`;

        // Append to container
        container.appendChild(avatar);
        container.appendChild(messageDiv);
        wrapper.appendChild(container);

        // Auto-hide after timeout
        if (timeoutSeconds && timeoutSeconds > 0) {
            setTimeout(() => {
                wrapper.innerHTML = '';
            }, timeoutSeconds * 1000);
        }
    }


    function notifyNewPosts(imageUrls = [], timeoutSeconds = 5, navigateUrl = "/feed") {
        const wrapper = document.getElementById('notificationArea');
        wrapper.innerHTML = ''; // Clear previous notifications

        // Create <a> tag instead of div for Livewire navigation
        const container = document.createElement('a');

        container.className = `
    bg-white dark:bg-gray-800 
    px-4 py-2 rounded-full shadow-lg flex items-center gap-4 border border-blue-400 dark:border-blue-600 
    animate-fade-drop cursor-pointer select-none max-w-xl mx-auto
    text-blue-700 dark:text-blue-400
    no-underline
  `.trim();

        if (navigateUrl) {
            container.href = navigateUrl;
            container.setAttribute('wire:navigate', navigateUrl);
        } else {
            container.href = '#';
        }

        // Avatar Group
        const avatarGroup = document.createElement('div');
        avatarGroup.className = 'flex -space-x-2';

        for (let i = 0; i < Math.min(3, imageUrls.length); i++) {
            const url = imageUrls[i];
            const avatar = document.createElement('div');
            avatar.className = `
      w-8 h-8 rounded-full border-2 border-white dark:border-gray-900 
      bg-blue-100 dark:bg-blue-900 flex items-center justify-center 
      text-sm font-semibold text-blue-800 dark:text-blue-300 overflow-hidden
    `.trim();

            if (url) {
                const img = document.createElement('img');
                img.src = url;
                img.alt = 'avatar';
                img.className = 'w-full h-full object-cover';
                avatar.appendChild(img);
            } else {
                const fallbackLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
                avatar.textContent = fallbackLetter;
            }

            avatarGroup.appendChild(avatar);
        }

        // Message and spinner
        const textWrapper = document.createElement('div');
        textWrapper.className = 'flex items-center gap-3';

        const messageSpan = document.createElement('span');
        messageSpan.className = 'font-semibold';
        messageSpan.textContent = 'New Posts';

        const spinner = document.createElement('div');
        spinner.className = 'flex items-center gap-1';

        ['animate-bar-scale-1', 'animate-bar-scale-2', 'animate-bar-scale-3'].forEach((cls, idx) => {
            const span = document.createElement('span');
            span.className = `w-[3px] h-5 rounded-full bg-blue-500/50 dark:bg-blue-400/50 ${cls}`;
            if (idx === 1) span.classList.add('h-[35px]', 'mx-[5px]');
            spinner.appendChild(span);
        });

        textWrapper.appendChild(messageSpan);
        textWrapper.appendChild(spinner);

        container.appendChild(avatarGroup);
        container.appendChild(textWrapper);
        wrapper.appendChild(container);

        // Auto-dismiss after timeoutSeconds
        if (timeoutSeconds && timeoutSeconds > 0) {
            setTimeout(() => {
                wrapper.innerHTML = '';
            }, timeoutSeconds * 1000);
        }
    }

    // Example usage:
    // notifyNewMessage({
    //     avatarUrl: 'https://example.com/user.jpg',
    //     messagePreview: 'Hey how are you doing today?',
    //     chatId: 42
    // });
</script>