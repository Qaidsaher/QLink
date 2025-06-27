<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Facades\Log;

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
        'post_created'        => 'created a post:',
        'post_updated'        => 'updated your post:',
        'post_deleted'        => 'deleted your post.',
        'post_liked'          => 'liked your post:',
        'post_unliked'        => 'unliked your post:',
        'commented'           => 'commented on your post:',
        'deleted_comment'     => 'deleted a comment on your post.',
        'shared_post'         => 'shared your post:',
        'bookmarked'          => 'bookmarked your post:',
        'mentioned'           => 'mentioned you in a post:',
        'tagged'              => 'tagged you in a post:',
        'followed'            => 'started following you.',
        'unfollowed'          => 'stopped following you.',
        'reacted'             => 'reacted to your post:',
        'sent_message'        => 'sent you a message.',
        'received_message'    => 'received your message.',
        'joined_group'        => 'joined your group.',
        'left_group'          => 'left your group.',
        'login'               => 'logged into the system.',
        'logout'              => 'logged out.',
        'register'            => 'registered an account.',
    ];


    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function toArray(object $notifiable): array
    {
         Log::info('UserNotification triggered', ['action' => $this->action]);
        $action   = $this->action;
        $message  = self::ACTIONS[$action] ?? 'performed an action.';
        // $icon     = $action); // uses your helper function

        $postTitle = $this->post->title ?? 'Untitled Post';
        $username  = $this->user->name ?? 'Someone';
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

        // Add comment text if available
        if (in_array($action, ['commented', 'deleted_comment']) && $this->extra) {
            $message .= " — \"{$this->extra}\"";
        }

        // Build URL if needed
        $url = null;
        if ($this->post?->exists) {
            $url = route('posts.show', $this->post);
        } elseif ($action === 'sent_message') {
            $url = route('messages', ['user' => $userId]);
        } elseif (in_array($action, ['followed', 'unfollowed'])) {
            $url = route('profile.show', ['user' => $userId]);
        }

        return [
            'user_id'   => $userId,
            'username'  => $username,
            // 'icon'      => $icon,
            'message'   => $message,
            'action'    => $action,
            'url'       => $url,
            'post_id'   => $this->post->id ?? null,
            'timestamp' => now()->toDateTimeString(),
            'extra'     => $this->extra ?? null,
        ];
    }
}
