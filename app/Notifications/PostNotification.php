<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
class PostNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Post $post,
        public string $action,
        public ?string $extra = null // for comment content if needed
    ) {}

    private const ACTIONS = [
        'created'         => 'created a post:',
        'updated'         => 'updated your post:',
        'deleted'         => 'deleted your post.',
        'liked'           => 'liked your post:',
        'unliked'         => 'unliked your post:',
        'commented'       => 'commented on your post:',
        'deleted_comment' => 'deleted a comment on your post.',
    ];

    private const ICONS = [
        'created'         => '🆕',
        'updated'         => '✏️',
        'deleted'         => '🗑️',
        'liked'           => '👍',
        'unliked'         => '👎',
        'commented'       => '💬',
        'deleted_comment' => '❌',
    ];

    private const COLORS = [
        'created'         => 'bg-green-500',
        'updated'         => 'bg-yellow-500',
        'deleted'         => 'bg-red-500',
        'liked'           => 'bg-blue-500',
        'unliked'         => 'bg-gray-500',
        'commented'       => 'bg-indigo-500',
        'deleted_comment' => 'bg-pink-500',
    ];

    public function via(object $notifiable): array
    {
        return ['database','broadcast']; // Save to DB, not email
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
    public function toArray(object $notifiable): array
    {
        $message = self::ACTIONS[$this->action];
        $icon    = self::ICONS[$this->action];
        $color   = self::COLORS[$this->action];

        $postTitle = $this->post->title ?? 'Untitled Post';

        if (in_array($this->action, ['created', 'updated', 'liked', 'unliked', 'commented'])) {
            $message .= " \"{$postTitle}\"";
        }

        if ($this->action === 'commented' && $this->extra) {
            $message .= " — \"{$this->extra}\"";
        }

        return [
            'icon'     => $icon,
            'message'  => $message,
            'color'    => $color,
            'url'      => $this->post->exists ? route('posts.show', $this->post) : null,
            'post_id'  => $this->post->id,
        ];
    }
}
