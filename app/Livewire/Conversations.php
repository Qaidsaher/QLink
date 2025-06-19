<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class Conversations extends Component
{
    public string $searchTerm = '';

    public function mount($user = null)
    {
        $this->conversations();
    }



    #[Computed(cache: true, key: 'conversations-list')]
    public function conversations(): Collection
    {
        $authId = Auth::id();

        // Step 1: Get all unique user IDs in conversation with the authenticated user
        $userIds = Message::where('sender_id', $authId)
            ->orWhere('receiver_id', $authId)
            ->selectRaw('DISTINCT CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as user_id', [$authId])
            ->pluck('user_id');

        if ($userIds->isEmpty()) {
            return collect();
        }

        // Step 2: Get the latest message for each conversation
        // Use CASE instead of LEAST/GREATEST for SQLite compatibility
        $dbDriver = DB::getDriverName();
        $user1 = "CASE WHEN sender_id < receiver_id THEN sender_id ELSE receiver_id END";
        $user2 = "CASE WHEN sender_id > receiver_id THEN sender_id ELSE receiver_id END";

        $lastMessages = Message::selectRaw("*, $user1 as user1, $user2 as user2")
            ->where(function ($query) use ($authId) {
                $query->where('sender_id', $authId)
                    ->orWhere('receiver_id', $authId);
            })
            ->orderByDesc('created_at')
            ->get()
            ->unique(function ($msg) {
                $low = min($msg->sender_id, $msg->receiver_id);
                $high = max($msg->sender_id, $msg->receiver_id);
                return "{$low}-{$high}";
            })
            ->keyBy(function ($msg) use ($authId) {
                return $msg->sender_id == $authId ? $msg->receiver_id : $msg->sender_id;
            });

        // Step 3: Get unread counts from each user to the authenticated user
        $unreadCounts = Message::select('sender_id', DB::raw('COUNT(*) as unread_count'))
            ->whereIn('sender_id', $userIds)
            ->where('receiver_id', $authId)
            ->where('is_read', false)
            ->groupBy('sender_id')
            ->pluck('unread_count', 'sender_id');

        // Step 4: Fetch user details and attach last message and unread count
        $users = User::whereIn('id', $userIds)->get()->map(function ($user) use ($lastMessages, $unreadCounts) {
            $lastMessage = $lastMessages[$user->id] ?? null;
            $user->last_message = $lastMessage->message ?? 'No messages yet.';
            $user->last_message_at = $lastMessage?->created_at;
            $user->unread_count = $unreadCounts[$user->id] ?? 0;
            return $user;
        });

        return $users->sortByDesc('last_message_at')->values(); // Reindex collection
    }


    #[Computed(key: 'search-results')]
    public function searchResults(): Collection
    {
        // This logic is unchanged
        if (empty(trim($this->searchTerm))) {
            return new Collection();
        }
        return User::where('id', '!=', Auth::id())
            ->where(fn($q) => $q->where('name', 'like', "%{$this->searchTerm}%")->orWhere('username', 'like', "%{$this->searchTerm}%"))
            ->take(5)
            ->get();
    }


    public function render()
    {
        return view('livewire.conversations',[
        'conversations' => $this->conversations(), // ✅ manually inject it
    ]);
    }
}
