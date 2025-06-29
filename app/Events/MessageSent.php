<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public Message $message;
    public User $sender;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->sender = $message->sender; // eager load sender
        if ($this->sender instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {
            $this->sender = $message->sender()->first();
        }
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('chat.' . $this->message->receiver_id)];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'chat_id' => $this->message->sender_id, // to open chat
            'message_preview' => $this->message->message,
            'sender_avatar' => $this->sender->avatarUrl(),
            'sender_name' => $this->sender->name,
        ];
    }
}
