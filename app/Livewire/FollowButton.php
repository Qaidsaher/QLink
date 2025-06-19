<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FollowButton extends Component
{
    public int $userIdToFollow;
    public bool $isFollowing;
    public bool $isSmall = false; // For styling a smaller button if needed

    public function mount(int $userIdToFollow, bool $isSmall = false)
    {
        $this->userIdToFollow = $userIdToFollow;
        $this->isSmall = $isSmall;
        $this->updateFollowStatus();
    }

    public function updateFollowStatus()
    {
        if (Auth::check() && Auth::id() !== $this->userIdToFollow) {
            $userToFollow = User::find($this->userIdToFollow); // Fetch the user to check against
            if ($userToFollow) {
                $this->isFollowing = Auth::user()->isFollowing($userToFollow);
            } else {
                $this->isFollowing = false; // User to follow not found
            }
        } else {
            $this->isFollowing = false;
        }
    }

    public function toggleFollow()
    {
        if (!Auth::check()) {
            // Optionally dispatch a notification to login
            return $this->dispatch('notify', ['message' => 'Please login to follow users.', 'type' => 'info']);
            // return redirect()->route('login');
        }

        $currentUser = Auth::user();
        if ($currentUser->id === $this->userIdToFollow) {
            return; // Cannot follow self
        }

        $userToFollow = User::find($this->userIdToFollow);
        if (!$userToFollow) {
            return $this->dispatch('notify', ['message' => 'User not found.', 'type' => 'error']);
        }

        if ($this->isFollowing) {
            $currentUser->following()->detach($userToFollow->id);
        } else {
            $currentUser->following()->attach($userToFollow->id);
        }
        $this->isFollowing = !$this->isFollowing;

        // Dispatch an event that the UserProfile component (or others) can listen to
        // This helps update follower counts on the main profile page if this button is used elsewhere.
        $this->dispatch('followStatusChanged');
    }

    public function render()
    {
        return view('livewire.follow-button');
    }
}
