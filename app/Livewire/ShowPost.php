<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Title;

#[Title('post show details ')]

class ShowPost extends Component
{
    use AuthorizesRequests; // For using $this->authorize()

    public Post $post;

    // State properties for better reactivity and performance
    public int $likesCount;
    public bool $isLikedByAuthUser;

    // State for the comment section
    public string $newCommentText = '';

    // State for the image modal
    public bool $imageModalOpen = false;
    public array $imageModalUrls = [];
    public int $currentImageModalIndex = 0;

    /**
     * Mounts the component, loads the post, and sets initial state.
     * Route model binding automatically injects the correct Post.
     */
    public function mount(Post $post)
    {
        $this->post = $post->load([
            'user',
            'attachments',
            'comments' => fn($q) => $q->with('user')->orderBy('created_at', 'desc'),
            'likes'
        ]);

        // Set initial state for reactivity
        $this->likesCount = $this->post->likes_count;
        $this->isLikedByAuthUser = $this->post->is_liked; // Assumes 'is_liked' attribute exists on Post model
    }

    /**
     * Toggles the like status for the post without a full DB refresh.
     * This is much faster and provides a better user experience.
     */
    public function toggleLike()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        if ($this->isLikedByAuthUser) {
            $this->post->likes()->where('user_id', Auth::id())->delete();
            $this->likesCount--;
            $this->isLikedByAuthUser = false;
        } else {
            $this->post->likes()->create(['user_id' => Auth::id()]);
            $this->likesCount++;
            $this->isLikedByAuthUser = true;
        }
    }

    /**
     * Adds a new comment and refreshes the post data to show it.
     */
    public function addComment()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $this->validate(['newCommentText' => 'required|string|max:2000']);

        $this->post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->newCommentText,
        ]);

        $this->newCommentText = '';
        $this->refreshPostData(); // Refresh to get new comment with user data
        $this->dispatch('comment-added'); // Optional: for JS to scroll to new comment
    }
    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // if (Auth::id() !== $comment->user_id) {
        //     abort(403); // unauthorized
        // }

        $comment->delete();
        $this->refreshPostData();
    }
    /**
     * Toggles the follow status for the post's author.
     */
    public function toggleFollow(User $userToFollow)
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $currentUser = Auth::user();
        if ($currentUser->id === $userToFollow->id) {
            return;
        }

        $currentUser->following()->toggle($userToFollow->id);
    }

    /**
     * Deletes the current post after authorization.
     */
    public function deletePost()
    {
        $this->authorize('delete', $this->post);
        $this->post->delete();
        session()->flash('status', 'Post was successfully deleted.');
        return $this->redirect(route('feed'), navigate: true);
    }

    /**
     * Helper to refresh the post's data from the database.
     */
    private function refreshPostData()
    {
        $this->post = $this->post->fresh([
            'user',
            'attachments',
            'comments' => fn($q) => $q->with('user')->orderBy('created_at', 'desc'),
            'likes'
        ]);

        // Also update the reactive properties
        $this->likesCount = $this->post->likes_count;
        $this->isLikedByAuthUser = $this->post->is_liked;
    }

    // --- Image Modal Methods (no changes needed) ---
    public function openImageModal(int $imageIndex)
    {
        $imageAttachments = $this->post->attachments->where('file_type', 'image')->values();
        if ($imageAttachments->isEmpty()) return;
        $this->imageModalUrls = $imageAttachments->map(fn($att) => ['url' => $att->file_url])->all();
        $this->currentImageModalIndex = $imageIndex;
        $this->imageModalOpen = true;
    }

    public function closeImageModal()
    {
        $this->imageModalOpen = false;
    }

    public function navigateImageModal(int $direction)
    {
        $newIndex = $this->currentImageModalIndex + $direction;
        if (isset($this->imageModalUrls[$newIndex])) {
            $this->currentImageModalIndex = $newIndex;
        }
    }

    public function render()
    {
        // Add a page title dynamically
        return view('livewire.show-post');;
    }
}
