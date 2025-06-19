<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UserNotification extends Notification implements ShouldQueue
{
    use Queueable;


    public function __construct(
        public string $action,
        public ?Post $post = null,
        public ?User $user = null,
        public ?string $extra = null
    ) {}

    private const ACTIONS = [
        'created'           => 'created a post:',
        'updated'           => 'updated your post:',
        'deleted'           => 'deleted your post.',
        'liked'             => 'liked your post:',
        'unliked'           => 'unliked your post:',
        'commented'         => 'commented on your post:',
        'deleted_comment'   => 'deleted a comment on your post.',
        'shared'            => 'shared your post:',
        'bookmarked'        => 'bookmarked your post:',
        'mentioned'         => 'mentioned you in a post:',
        'tagged'            => 'tagged you in a post:',
        'followed'          => 'started following you.',
        'unfollowed'        => 'stopped following you.',
        'reacted'           => 'reacted to your post:',
        'sent_message'      => 'sent you a message.',
        'received_message'  => 'received your message.',
        'joined_group'      => 'joined your group.',
        'left_group'        => 'left your group.',
    ];

    private const ICONS = [
        'created'           => '🆕',
        'updated'           => '📝',
        'deleted'           => '🗑️',
        'liked'             => '❤️',
        'unliked'           => '💔',
        'commented'         => '💬',
        'deleted_comment'   => '❌💬',
        'shared'            => '🔁',
        'bookmarked'        => '🔖',
        'mentioned'         => '📣',
        'tagged'            => '🏷️',
        'followed'          => '👤➕',
        'unfollowed'        => '👤➖',
        'reacted'           => '😊',
        'sent_message'      => '📤',
        'received_message'  => '📥',
        'joined_group'      => '👥➕',
        'left_group'        => '👥➖',
    ];


    private const COLORS = [
        'created'           => 'bg-green-500',
        'updated'           => 'bg-yellow-500',
        'deleted'           => 'bg-red-500',
        'liked'             => 'bg-pink-500',
        'unliked'           => 'bg-gray-500',
        'commented'         => 'bg-indigo-500',
        'deleted_comment'   => 'bg-rose-500',
        'shared'            => 'bg-blue-600',
        'bookmarked'        => 'bg-amber-500',
        'mentioned'         => 'bg-purple-500',
        'tagged'            => 'bg-emerald-500',
        'followed'          => 'bg-teal-500',
        'unfollowed'        => 'bg-slate-500',
        'reacted'           => 'bg-orange-500',
        'sent_message'      => 'bg-cyan-500',
        'received_message'  => 'bg-sky-500',
        'joined_group'      => 'bg-lime-500',
        'left_group'        => 'bg-zinc-500',
    ];


    public function via(object $notifiable): array
    {
        return ['database', 'broadcast']; // Save to DB, not email
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
    public function toArray(object $notifiable): array
    {
        $action   = $this->action;
        $message  = self::ACTIONS[$action] ?? 'performed an action.';
        $icon     = self::ICONS[$action] ?? 'ℹ️';
        $color    = self::COLORS[$action] ?? 'bg-gray-400';

        $postTitle = $this->post->title ?? 'Untitled Post';
        $username  = $this->user->name ?? 'Someone'; // assuming $this->user exists
        $userId    = $this->user->id ?? null;

        // Add post title to relevant actions
        if (in_array($action, [
            'created',
            'updated',
            'liked',
            'unliked',
            'commented',
            'shared',
            'bookmarked',
            'reacted',
            'mentioned',
            'tagged'
        ])) {
            $message .= " \"{$postTitle}\"";
        }

        // Add comment/extra text if available
        if (in_array($action, ['commented', 'deleted_comment']) && $this->extra) {
            $message .= " — \"{$this->extra}\"";
        }

        // Fallback or specific route based on action
        $url = null;
        if ($this->post?->exists) {
            $url = route('posts.show', $this->post);
        } elseif ($action === 'sent_message') {
            $url = route('messages', ['user' => $userId]);
        } elseif ($action === 'followed' || $action === 'unfollowed') {
            $url = route('profile.show', ['user' => $userId]);
        }

        return [
            'user_id'   => $userId,
            'username'  => $username,
            'icon'      => $icon,
            'color'     => $color,
            'message'   => $message,
            'action'    => $action,
            'url'       => $url,
            'post_id'   => $this->post->id ?? null,
            'timestamp' => now()->toDateTimeString(),
            'extra'     => $this->extra ?? null,
        ];
    }
}
