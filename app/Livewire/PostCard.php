<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\User; // For type hinting if needed directly
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // For deleting attachments
use Livewire\Component;

class PostCard extends Component
{
    public Post $post; // Type hint the Post model
    public bool $isLikedByCurrentUser;
    public int $likesCount;
    public int $commentsCount;

    // For showing the "more options" dropdown (using Alpine.js, but Livewire can manage state too)
    // public bool $showOptions = false;

    // Listener for when a comment is added to this specific post from elsewhere (e.g., a modal)
    protected $listeners = ['commentAddedToPost'];

    public function mount(Post $post) // Dependency injection of the Post model
    {
        $this->post = $post; // Load all necessary relationships in the parent component or route
        $this->updateInteractionStats();
    }

    public function updateInteractionStats()
    {
        // Ensure relationships are loaded if not already.
        // $this->post->loadMissing(['likes', 'comments']); // Usually loaded by PostFeed
        $this->likesCount = $this->post->likes_count ?? $this->post->likes()->count(); // Use accessor if available
        $this->commentsCount = $this->post->comments_count ?? $this->post->comments()->count();
        $this->isLikedByCurrentUser = Auth::check() ? $this->post->isLikedBy(Auth::user()) : false;
    }

    public function toggleLike()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->isLikedByCurrentUser) {
            $this->post->likes()->where('user_id', Auth::id())->delete();
            // session()->flash('toast-message', 'Post unliked!'); // Use a toast for less intrusive feedback
        } else {
            $this->post->likes()->create(['user_id' => Auth::id()]);
            // session()->flash('toast-message', 'Post liked!');
            // TODO: Add notification logic here if desired
        }
        $this->updateInteractionStats(); // Re-fetch like status and count
        $this->emit('likeToggled'); // Emit generic event if other parts of app need to know
    }

    public function deletePost()
    {
        // Authorization: Ensure only owner or admin/moderator can delete
        // This uses a simple check, for more complex scenarios, use Laravel Policies
        if (!Auth::check() || (Auth::id() !== $this->post->user_id && !(Auth::user()->role === 'admin' || Auth::user()->role === 'moderator'))) {
            // You can flash a message or handle unauthorized attempts
            session()->flash('toast-error', 'You are not authorized to delete this post.');
            return;
        }

        // Delete attachments from storage first
        foreach ($this->post->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        // Attachments table records, likes, comments should be deleted by database cascade constraints
        // If not, delete them manually:
        // $this->post->attachments()->delete();
        // $this->post->likes()->delete();
        // $this->post->comments()->delete();

        $this->post->delete();

        $this->emitTo('post-feed', 'postDeleted', $this->post->id); // Emit to PostFeed to remove it
        // $this->emit('postDeletedGlobal', $this->post->id); // Or a global event
        session()->flash('toast-message', 'Post deleted successfully.');
        // The component instance will be removed from the DOM by PostFeed refreshing.
    }

    // Called if a comment is added externally (e.g., from a full post page or modal)
    // and we want to update the comment count on this card.
    public function commentAddedToPost($postId)
    {
        if ($this->post->id === $postId) {
            $this->updateInteractionStats(); // Just re-fetch counts
        }
    }

    public function render()
    {
        return view('livewire.post-card');
    }
}
