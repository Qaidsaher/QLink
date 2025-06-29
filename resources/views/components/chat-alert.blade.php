<script>

    function notifyNewMessage({ avatarUrl = null, messagePreview = '', chatId, senderName = 'مستخدم' }, timeoutSeconds = 8) {
        const wrapper = document.getElementById('messageNotificationArea');
        wrapper.innerHTML = ''; // Clear existing notification

        // Create notification container <a>
        const container = document.createElement('div');
        container.className = `
        relative bg-white dark:bg-gray-800 px-4 py-2 rounded-md shadow-md 
        flex items-center gap-4 border border-green-400 dark:border-green-600 
        animate-fade-drop cursor-pointer max-w-sm w-full mb-2 text-gray-800 dark:text-white
    `.trim();

        // Link wrapper
        const link = document.createElement('a');
        link.href = `/messages/chat/${chatId}`;
        link.setAttribute('wire:navigate', '');
        link.className = 'flex items-center gap-2 flex-grow no-underline';

        // Avatar
        const avatar = document.createElement('div');
        avatar.className = `
        w-10 h-10 rounded-full border-2 overflow-hidden 
        border-white dark:border-gray-700 flex items-center justify-center 
        text-sm font-semibold text-green-800 dark:text-green-200 bg-green-100 dark:bg-green-900
        flex-shrink-0
    `.trim();

        if (avatarUrl) {
            const img = document.createElement('img');
            img.src = avatarUrl;
            img.alt = 'avatar';
            img.className = 'w-full h-full object-cover';
            avatar.appendChild(img);
        } else {
            const firstLetter = senderName?.charAt(0).toUpperCase() || '?';
            avatar.textContent = firstLetter;
        }

        // Message content
        const messageDiv = document.createElement('div');
        messageDiv.className = 'text-sm';
        const messagePreviewText = messagePreview.split(' ').slice(0, 3).join(' ') + '...';
        messageDiv.innerHTML = `
        <span class="block font-bold">New message</span>
        <span>${messagePreviewText}</span>
    `;

        // Append avatar and message to link
        link.appendChild(avatar);
        link.appendChild(messageDiv);

        // Dismiss icon
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '&times;';
        closeBtn.className = `
        absolute top-1 right-2 text-lg font-bold 
        text-gray-400 hover:text-gray-700 dark:hover:text-white
    `;
        closeBtn.addEventListener('click', () => {
            wrapper.innerHTML = '';
        });

        // Assemble all
        container.appendChild(link);
        container.appendChild(closeBtn);
        wrapper.appendChild(container);

        // Auto-dismiss after timeout
        if (timeoutSeconds && timeoutSeconds > 0) {
            setTimeout(() => {
                wrapper.innerHTML = '';
            }, timeoutSeconds * 1000);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        @auth
            window.Echo.private(`chat.{{ auth()->id() }}`)
                .listen('.message.sent', (e) => {
                    notifyNewMessage({
                        avatarUrl: e.sender_avatar,
                        messagePreview: e.message_preview,
                        chatId: e.chat_id
                    });
                });
        @endauth
});


</script>