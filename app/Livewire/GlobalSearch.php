<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Post;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection; // For type hinting

class GlobalSearch extends Component
{
    public string $query = '';
    public EloquentCollection $users;
    public EloquentCollection $posts;
    public bool $showResults = false;

    protected $queryString = [
        'query' => ['except' => '', 'as' => 'q'], // Keep query in URL as 'q' if desired
    ];

    public function mount()
    {
        $this->users = new EloquentCollection();
        $this->posts = new EloquentCollection();
    }

    public function updatedQuery()
    {
        // Reset results if query is too short or empty
        if (strlen($this->query) < 2) {
            $this->resetResults();
            return;
        }

        $this->showResults = true;

        // Search Users
        $this->users = User::where('name', 'like', '%' . $this->query . '%')
            ->orWhere('username', 'like', '%' . $this->query . '%')
            ->select('id', 'name', 'username', 'avatar') // Use 'avatar' for path
            ->take(5) // Limit number of user results
            ->get();

        // Search Posts (simple content search)
        $this->posts = Post::where('content', 'like', '%' . $this->query . '%')
            ->with('user:id,name,username,avatar') // Eager load post author with specific fields
            ->select('id', 'user_id', 'content', 'created_at') // Select relevant post fields
            ->orderBy('created_at', 'desc')
            ->take(5) // Limit number of post results
            ->get();

        // If no results at all, you might want to hide the dropdown
        if ($this->users->isEmpty() && $this->posts->isEmpty()) {
            // Keep showResults true to show "No results" message, or set to false to hide
            // $this->showResults = false;
        }
    }

    public function hideResults()
    {
        // Delay hiding to allow click on result items
        // This is often better handled by Alpine.js @click.away
        // For now, a simple hide. If using Alpine, this might not be needed.
        // $this->showResults = false;
    }

    public function resetResults()
    {
        $this->query = ''; // Also clear the query itself upon full reset
        $this->users = new EloquentCollection();
        $this->posts = new EloquentCollection();
        $this->showResults = false;
    }

    public function viewAllResults()
    {
        // Redirect to a dedicated search results page
        if (strlen($this->query) >= 2) {
            return redirect()->route('search.results', ['query' => $this->query]);
        }
    }

    public function render()
    {
        return view('livewire.global-search');
    }
}
