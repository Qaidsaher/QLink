<?php

namespace App\Events;
use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostActionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

   use SerializesModels;

    public Post $post;
    public string $action;

    public function __construct(Post $post, string $action)
    {
        $this->post = $post;
        $this->action = $action;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('App.Models.User.' . $this->post->user_id);
    }

    public function broadcastWith(): array
    {
        return [
            'post_id' => $this->post->id,
            'action'  => $this->action,
            'title'   => $this->post->title,
        ];
    }

    public function broadcastAs(): string
    {
        return 'post.action';
    }
}
