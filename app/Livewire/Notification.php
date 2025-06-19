<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Attributes\Title;
#[Title('Notifications')]
class Notification extends Component
{
    use WithPagination;

    public string $filter = 'all';

    public function getListeners(): array
    {
        return [
            "echo-private:App.Models.User." . auth()->id() . ",.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'handleNewNotification',
        ];
    }

    public function handleNewNotification()
    {
        $this->refreshCounts();
        $this->resetPage();
    }

    #[Computed(persist: true, seconds: 300)]
    public function allCount(): int
    {
        return Auth::user()->notifications()->count();
    }

    #[Computed(persist: true, seconds: 300)]
    public function unreadCount(): int
    {
        return Auth::user()->unreadNotifications()->count();
    }

    // NEW: Add a computed property for the 'Read' count
    #[Computed(persist: true, seconds: 300)]
    public function readCount(): int
    {
        return Auth::user()->notifications()->whereNotNull('read_at')->count();
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function markAsRead(string $id): void
    {
        if ($notification = Auth::user()->notifications()->find($id)) {
            $notification->markAsRead();
            $this->refreshCounts();
        }
    }

    public function markAsReadAndRedirect(string $id)
    {
        if ($notification = Auth::user()->notifications()->find($id)) {
            $notification->markAsRead();
            $this->refreshCounts();

            if (isset($notification->data['url'])) {
                return $this->redirect($notification->data['url'], navigate: true);
            }
        }
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        $this->refreshCounts();
    }

    public function deleteNotification(string $id): void
    {
        if ($notification = Auth::user()->notifications()->find($id)) {
            $notification->delete();
            $this->refreshCounts();
            $this->resetPage();
        }
    }

    #[Computed]
    public function notifications()
    {
        $query = Auth::user()->notifications()->latest();

        if ($this->filter === 'unread') {
            return $query->whereNull('read_at')->paginate(10);
        }

        // UPDATED: Handle the new 'read' filter
        if ($this->filter === 'read') {
            return $query->whereNotNull('read_at')->paginate(10);
        }

        return $query->paginate(10);
    }

    public function refreshCounts(): void
    {
        unset($this->allCount);
        unset($this->unreadCount);
        unset($this->readCount); // UPDATED: Unset the new read count too
    }

    public function render()
    {
        return view('livewire.notification');
    }
}
