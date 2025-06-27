<?php

namespace App\Livewire;

use App\Events\PostActionEvent;
use App\Events\PostUpdated;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination; // For potential future pagination
use Livewire\Attributes\On;

#[Title('posts')]
class Posts extends Component
{
    // use WithPagination; // We'll use a "load more" approach initially
    public $userId;
    public $posts; // Collection of posts
    public $perPage = 10; // Number of posts to load each time
    public $page = 1; // Current page for loading more
    public $hasMorePages;

    public $newCommentText = []; // Array to store comment text for each post [postId => text]
    public $openCommentsSection = []; // Array to store which comment section is open [postId => boolean]

    // For image modal
    public $imageModalOpen = false;
    public $imageModalUrls = []; // Array of {url: string, originalPostId: int}
    public $currentImageModalIndex = 0;

    protected $listeners = ['scrollBottom' => 'loadMore'];

    public function mount($userId = null)
    {
        $this->userId = $userId;
        $this->loadPosts();
    }

    public function loadPosts($isInitialLoad = true)
    {

        $query = Post::with([
            'user',
            'attachments',
            'comments' => function ($query) {
                $query->with('user')->orderBy('created_at', 'desc');
            },
            'likes'
        ])
            ->orderBy('created_at', 'desc');

        // Filter by user ID if provided


        $loadedPosts = $query->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();

        if ($isInitialLoad) {
            $this->posts = $loadedPosts;
        } else {
            $this->posts = $this->posts->concat($loadedPosts);
        }

        $this->hasMorePages = $loadedPosts->count() >= $this->perPage;

        // Initialize comment text and open state for new posts
        foreach ($loadedPosts as $post) {
            if (!isset($this->newCommentText[$post->id])) {
                $this->newCommentText[$post->id] = '';
            }
            if (!isset($this->openCommentsSection[$post->id])) {
                $this->openCommentsSection[$post->id] = false;
            }
        }
    }

    public function loadMore()
    {
        // sleep(1);
        if ($this->hasMorePages) {
            $this->page++;
            $this->loadPosts(false);
        }
    }

    public function toggleLike(Post $post)
    {
        if (!Auth::check()) {
            // Or redirect to login, show a message, etc.
            // $this->dispatch('toast', ['message' => 'Please login to like posts.', 'type' => 'info']);
            return;
        }

        $user = Auth::user();

        if ($post->isLikedBy($user)) {
            $post->likes()->where('user_id', $user->id)->delete();
            // Auth::user()->notify(instance: new UserNotification('post_unliked', $post, Auth::user(), 'you unlike it'));
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            // Auth::user()->notify(instance: new UserNotification(  'post_liked', $post, Auth::user(), 'you like it'));
        }

        // Reload the specific post's like data or the entire post
        // A simple way is to reload the post, but for performance, you might update counts directly
        $this->refreshPost($post->id);
        //  broadcast(new PostUpdated($post,'updated'));

    }

    public function toggleSave(Post $post)
    {
        // Placeholder for save functionality
        // You'll need a SavedPost model and relationship
        // $this->dispatch('toast', ['message' => 'Save functionality coming soon!', 'type' => 'info']);
    }

    public function toggleFollow(User $userToFollow)
    {
        if (!Auth::check()) {
            return;
        }
        $currentUser = Auth::user();

        if ($currentUser->id === $userToFollow->id) {
            return; // Cannot follow self
        }

        if ($currentUser->isFollowing($userToFollow)) {
            $currentUser->following()->detach($userToFollow->id);

            // Auth::user()->notify(instance: new UserNotification('unfollowed'));
        } else {
            $currentUser->following()->attach($userToFollow->id);
            // Auth::user()->notify(instance: new UserNotification('followed'));
        }
        // No need to refresh all posts, just the UI for this user, which happens automatically if
        // the button's display logic depends on $currentUser->isFollowing($userToFollow)
        // However, if the `following` status is on the $post->user object, you might need to refresh posts
        // or update that user's status within the $this->posts collection.
        // For simplicity, we can let Livewire re-render.
        // Or specifically update the user object in the posts collection:
        $this->posts = $this->posts->map(function ($p) use ($userToFollow) {
            if ($p->user_id === $userToFollow->id) {
                // This is tricky as $userToFollow is a different instance.
                // It's often better to just re-fetch or ensure the view correctly uses Auth::user()->isFollowing()
            }
            return $p;
        });
    }

    public function addComment(Post $post)
    {
        if (!Auth::check()) {
            // $this->dispatch('toast', ['message' => 'Please login to comment.', 'type' => 'info']);
            return;
        }

        $this->validate([
            "newCommentText.{$post->id}" => 'required|string|max:1000',
        ]);

        $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->newCommentText[$post->id],
        ]);

        $this->newCommentText[$post->id] = ''; // Clear input
        $this->openCommentsSection[$post->id] = true; // Keep comments open
        $this->refreshPost($post->id); // Reload post with new comment
        //  broadcast(new PostUpdated($post,'updated'));
        //  Auth::user()->notify(instance: new UserNotification('commented', $post, Auth::user()));

    }

    public function toggleComments(Post $post)
    {
        $this->openCommentsSection[$post->id] = !($this->openCommentsSection[$post->id] ?? false);
        if ($this->openCommentsSection[$post->id] && $post->comments->isEmpty()) {
            // If opening and comments were not loaded or empty, refresh to get them
            $this->refreshPost($post->id);
            //  broadcast(new PostUpdated($post,'updated'));

        }
    }
    public function deleteComment($commentId, $postId)
    {
        $comment = Comment::findOrFail($commentId);
        $post = Post::findOrFail($postId);
        if (Auth::id() !== $comment->user_id) {
            abort(403); // unauthorized
        }

        $comment->delete();
        $this->refreshPost($postId);
        //         Auth::user()->notify(instance: new UserNotification('comment_deleted', $post, Auth::user()));

        //  broadcast(new PostUpdated($post,'updated'));
    }

    #[On('deletePost')]
    public function deletePost($postId)
    {
        $post = Post::findOrFail($postId);
        if (Auth::id() !== $post->user_id) {
            abort(403); // unauthorized
        }
        $post->delete();
        $this->loadPosts();
        $this->js("
            showToast({ 
            type: 'success', 
            title: 'Post Deleted!', 
            message: 'The post has been successfully deleted.' 
            })
        ");
    }
    #[On('postCreated')]
    public function prependPost($postId)

    {
        // Fetch the newly created post with all its relationships
        $post = Post::with([
            'user',
            'attachments',
            'comments' => fn($q) => $q->with('user')->orderBy('created_at', 'desc'),
            'likes'
        ])->find($postId);

        if ($post && !$this->posts->contains('id', $postId)) {
            // Initialize its state for comments, etc.
            $this->initializePostState($post);

            // Prepend it to the existing collection
            $this->posts->prepend($post);
        }
    }

    /**
     * Helper to initialize state for a new post.
     */
    private function initializePostState(Post $post): void
    {
        if (!isset($this->newCommentText[$post->id])) {
            $this->newCommentText[$post->id] = '';
        }
        if (!isset($this->openCommentsSection[$post->id])) {
            $this->openCommentsSection[$post->id] = false;
        }
    }

    // #[On('postUpdated')]
    // public function postUpdated($postId)
    // {
    //     $this->refreshPost($postId);
    // }
    public function refreshPost($postId)
    {
        $freshPost = Post::with([
            'user',
            'attachments',
            'comments' => fn($q) => $q->with('user')->orderBy('created_at', 'desc'),
            'likes'
        ])->find($postId);

        if ($freshPost) {
            $this->posts = $this->posts->map(function ($p) use ($freshPost) {
                return $p->id === $freshPost->id ? $freshPost : $p;
            });
        }
    }


    // --- Image Modal Methods ---
    public function openImageModal($postId, $imageIndex)
    {
        $post = $this->posts->firstWhere('id', $postId);
        if (!$post || $post->attachments->where('file_type', 'image')->isEmpty()) {
            return;
        }

        $this->imageModalUrls = $post->attachments
            ->where('file_type', 'image')
            ->values() // Re-index collection
            ->map(function ($attachment, $index) use ($postId) {
                return ['url' => $attachment->file_url, 'originalPostId' => $postId, 'originalIndex' => $index];
            })->all();

        $this->currentImageModalIndex = $imageIndex;
        $this->imageModalOpen = true;
        $this->dispatch('imageModalOpened'); // For potential JS body scroll lock
    }

    public function closeImageModal()
    {
        $this->imageModalOpen = false;
        $this->dispatch('imageModalClosed');
    }

    public function navigateImageModal($direction)
    {
        $newIndex = $this->currentImageModalIndex + $direction;
        if ($newIndex >= 0 && $newIndex < count($this->imageModalUrls)) {
            $this->currentImageModalIndex = $newIndex;
        }
    }
    public function render()
    {
        return view('livewire.posts')->with(['title' => 'home']);
    }
}
