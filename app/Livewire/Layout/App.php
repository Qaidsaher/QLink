<?php

namespace App\Livewire\Layout;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class App extends Component
{
    public int $unreadMessages = 0;
    public int $unreadNotifications = 0;

    public function mount()
    {
        $this->updateCounts();
    }

    public function updateCounts()
    {
        $user = Auth::user();

        if ($user) {
            $this->unreadMessages = Message::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();

            $this->unreadNotifications = $user->unreadNotifications()->count(); // Laravel's default
        }
    }

    protected $listeners = [
        'messageReceived' => 'updateCounts',
        'notificationReceived' => 'updateCounts',
        'refreshUnreadCounts' => 'updateCounts', // manually trigger from anywhere
    ];


    public function render()
    {
        return view('livewire.layout.app');
    }
}
