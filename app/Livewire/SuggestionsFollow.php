<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection; // Important for type hinting

class SuggestionsFollow extends Component
{
    public Collection $suggestions; // Use Eloquent Collection for type safety
    public int $initialCount = 2; // How many to show initially
    public int $loadMoreCount = 2; // How many to load on "show more"
    public int $displayCount;
    public bool $hasMoreSuggestions = true;

    public function mount()
    {
        if (!Auth::check()) {
            $this->suggestions = new Collection(); // Empty collection if no user logged in
            $this->hasMoreSuggestions = false;
            return;
        }

        $this->displayCount = $this->initialCount;
        $this->loadSuggestions();
    }

    protected function getBaseQuery()
    {
        $authUserId = Auth::id();
        $followingIds = Auth::user()->following()->pluck('users.id')->all(); // Get IDs of users auth user is already following

        return User::query()
            ->where('id', '!=', $authUserId) // Exclude the authenticated user
            ->whereNotIn('id', $followingIds) // Exclude users already followed by auth user
            ->select(['id', 'name', 'username', 'avatar']); // Select only necessary fields
            // Add any other criteria for "good" suggestions, e.g.:
            // ->where('is_active', true)
            // ->withCount('followers') // If you want to order by popularity
            // ->orderByDesc('followers_count')
    }

    public function loadSuggestions()
    {
        if (!Auth::check()) return;

        $newSuggestions = $this->getBaseQuery()
            ->inRandomOrder() // For variety, can be changed to a more sophisticated logic
            ->take($this->displayCount)
            ->get();

        $this->suggestions = $newSuggestions;

        // Check if there are more suggestions than currently displayed
        // We fetch one more than displayCount to see if there are more available
        $potentialTotal = $this->getBaseQuery()->count();
        $this->hasMoreSuggestions = $this->suggestions->count() < $potentialTotal && $this->suggestions->count() > 0;

        // A simpler way if you don't want to recount all potential suggestions:
        // Fetch displayCount + 1 to check if more exist
        // $checkMore = $this->getBaseQuery()->take($this->displayCount + 1)->get();
        // $this->suggestions = $checkMore->take($this->displayCount);
        // $this->hasMoreSuggestions = $checkMore->count() > $this->displayCount;
    }


    public function loadMore()
    {
        if (!Auth::check() || !$this->hasMoreSuggestions) return;

        $this->displayCount += $this->loadMoreCount;
        $this->loadSuggestions();
    }

    public function toggleFollow(int $userIdToFollow)
    {
        if (!Auth::check()) return;

        $userToFollow = User::find($userIdToFollow);
        if (!$userToFollow) return;

        Auth::user()->following()->toggle($userToFollow->id);

        // Refresh suggestions to remove the followed user or add if unfollowed
        // Or, more efficiently, update the specific suggestion in the collection if you manage 'is_followed' state locally
        // For simplicity and correctness with the query, reloading is safer here.
        $this->loadSuggestions();

        // You might want to emit an event if other parts of the page need to know about the follow action
        $this->dispatch('userFollowStateChanged', $userIdToFollow, Auth::user()->isFollowing($userToFollow));
    }

    public function render()
    {
        return view('livewire.suggestions-follow');
    }
}
