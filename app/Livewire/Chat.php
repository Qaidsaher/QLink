<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Chat extends Component
{
    public ?User $selectedConversation = null;
    public string $newMessage = '';

    public function mount($user = null)
    {
        if ($user) {
            $this->loadConversation((int) $user);
        }
    }


    public function loadConversation(int $userId): void
    {
        $this->selectedConversation = User::find($userId);
        if ($this->selectedConversation) {
            // Mark messages as read
            Message::where('sender_id', $this->selectedConversation->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            // Dispatch event for Alpine.js to scroll to bottom
            $this->dispatch('chat-loaded');
        }
    }

    #[Computed(key: 'chat-messages')]
    public function chatMessages(): Collection
    {
        // This logic is unchanged
        if (!$this->selectedConversation) {
            return new Collection([]);
        }
        return Message::where(fn($q) => $q->where('sender_id', Auth::id())->where('receiver_id', $this->selectedConversation->id))
            ->orWhere(fn($q) => $q->where('sender_id', $this->selectedConversation->id)->where('receiver_id', Auth::id()))
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage(): void
    {
        // This logic is unchanged
        $this->validate(['newMessage' => 'required|string|max:1000']);
        if (!$this->selectedConversation) return;

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedConversation->id,
            'message' => $this->newMessage,
        ]);
        // broadcast event
        broadcast(new MessageSent($message))->toOthers();
        $this->reset('newMessage');

        // Dispatch event for Alpine.js to scroll to bottom
        $this->dispatch('message-sent');
    }

    public function deleteMessage(int $messageId): void
    {
        // This logic is unchanged
        $message = Message::find($messageId);
        if ($message && $message->sender_id === Auth::id()) {
            $message->delete();
        }
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
