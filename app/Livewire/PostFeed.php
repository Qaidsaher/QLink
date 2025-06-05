<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostFeed extends Component
{
    use WithPagination;

    public $perPage = 10;
    // Listen for events to refresh the feed
    protected $listeners = [
        'postCreated' => 'prependPost', // Or just '$refresh' for simplicity initially
        'postDeleted' => 'removePost',  // Or just '$refresh'
        'echo:posts,PostCreatedEvent' => 'prependPostFromBroadcast', // For real-time (Phase 2)
        'echo:posts,PostDeletedEvent' => 'removePostFromBroadcast', // For real-time (Phase 2)
        'echo:posts,.PostLikedEvent' => 'refreshPostLikes', // Real-time like updates
    ];

    public function loadMore()
    {
        $this->perPage += 10;
    }

    // Method to add a newly created post to the top without full refresh
    // This requires the 'postCreated' event to pass the post ID
    public function prependPost($postId)
    {
        // This is a simple way, but for pagination it can get tricky.
        // A full '$refresh' is often easier to manage initially.
        // To properly prepend, you'd fetch the new post and add it to the collection.
        // For now, let's stick to $refresh for simplicity triggered by other components.
        // This method can be enhanced later for true prepending.
        $this->resetPage(); // Go back to page 1 to see the new post at top
        // $this->setPage(1); // Alternative for Livewire pagination
    }

    public function removePost($postId)
    {
        // Similar to prependPost, a full $refresh is simpler.
        // To specifically remove, you'd filter your current posts collection.
        // For now, $refresh (by making listener '$refresh') is fine.
        // No action needed here if listener is just '$refresh'
    }

    // --- Methods for Real-time Broadcasting (Setup Laravel Echo later) ---
    public function prependPostFromBroadcast($eventData)
    {
        // $newPost = Post::with(['user', 'attachments'])->withCount(['comments', 'likes'])->find($eventData['post']['id']);
        // if ($newPost) {
        //    // Logic to prepend to the current view's posts if using manual collection management
        // }
        $this->dispatchBrowserEvent('log-pusher', ['message' => 'PostCreatedEvent received', 'data' => $eventData]);
        $this->resetPage(); // Simplest way to show new post
    }
    public function removePostFromBroadcast($eventData)
    {
        $this->dispatchBrowserEvent('log-pusher', ['message' => 'PostDeletedEvent received', 'data' => $eventData]);
        // $this->posts = $this->posts->where('id', '!==', $eventData['postId']); // If managing collection manually
        $this->resetPage(); // Simplest way
    }
    public function refreshPostLikes($eventData)
    {
        $this->dispatchBrowserEvent('log-pusher', ['message' => 'PostLikedEvent received', 'data' => $eventData]);
        // Find the post in the current collection and update its like count / status
        // This is more efficient than a full $refresh for just likes.
        // For simplicity, could also just $refresh.
        // This would require more complex state management in the PostCard or here.
        // A simple $refresh will work but is less optimized for just a like.
        // To specifically update one card:
        $this->emitTo('post-card', 'updateLikeStatsForPost', $eventData['post_id']);
    }
    // --- End Real-time Methods ---


    public function render()
    {
        $posts = Post::with([
                'user', // Eager load user
                'attachments', // Eager load attachments
                // 'comments' => function ($query) { // Optionally load few sample comments
                //     $query->with('user')->latest()->take(2);
                // }
            ])
            ->withCount(['comments', 'likes']) // Get counts efficiently
            ->latest() // Order by newest
            ->paginate($this->perPage);

        return view('livewire.post-feed', [
            'posts' => $posts,
        ]);
    }
}
