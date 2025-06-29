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
use Livewire\WithPagination;
use Livewire\Attributes\On;

#[Title('posts')]
class Posts extends Component
{
    // use WithPagination; // We'll use a "load more" approach initially
    public $userId;
    public $posts; // Collection of posts
    public $perPage = 10;
    public $page = 1;
    public $hasMorePages;
    public $newPostsCount = 0; //  <-- New property for the new posts counter

    public $newCommentText = [];
    public $openCommentsSection = [];

    // For image modal
    public $imageModalOpen = false;
    public $imageModalUrls = [];
    public $currentImageModalIndex = 0;

    protected $listeners = ['scrollBottom' => 'loadMore'];
    // Add these new properties at the top of your Posts.php class
    public $confirmingCommentDeletion = false;
    public $commentIdToDelete = null;

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

        $loadedPosts = $query->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();

        if ($isInitialLoad) {
            $this->posts = $loadedPosts;
        } else {
            $this->posts = $this->posts->concat($loadedPosts);
        }

        $this->hasMorePages = $loadedPosts->count() >= $this->perPage;

        foreach ($loadedPosts as $post) {
            if (!isset($this->newCommentText[$post->id])) {
                $this->newCommentText[$post->id] = '';
            }
            if (!isset($this->openCommentsSection[$post->id])) {
                $this->openCommentsSection[$post->id] = false;
            }
        }
    }

    /**
     * This method is called by Livewire's polling feature. It efficiently counts
     * new posts without loading the entire collection, updating a counter
     * that is displayed to the user.
     */
    public function checkForNewPosts()
    {
        $latestPostId = $this->posts->max('id');
        if ($latestPostId) {
            $this->newPostsCount = Post::where('id', '>', $latestPostId)->count();
        }
    }

    /**
     * This method is called when the user clicks the "show new posts" button.
     * It loads the new posts and prepends them to the existing collection,
     * then resets the new posts counter.
     */
    public function prependNewPosts()
    {
        $latestPostId = $this->posts->max('id');
        if ($latestPostId) {
            $newPosts = Post::with(['user', 'attachments', 'comments.user', 'likes'])
                ->where('id', '>', $latestPostId)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($newPosts as $post) {
                $this->initializePostState($post);
            }

            $this->posts = $newPosts->concat($this->posts);
            $this->newPostsCount = 0;
        }
    }


    // Add this new method to handle the confirmation request
    public function requestDeleteConfirmation($commentId)
    {
        $this->commentIdToDelete = $commentId;
        $this->confirmingCommentDeletion = true;
    }

    // Replace your OLD deleteComment method with this NEW one.
    // This new version works with the confirmation modal.
    public function deleteComment()
    {
        if ($this->commentIdToDelete === null) {
            return; // Do nothing if no comment is selected
        }

        $comment = Comment::findOrFail($this->commentIdToDelete);

        if (Auth::id() !== $comment->user_id) {
            abort(403); // Unauthorized
        }

        $postId = $comment->post_id;
        $comment->delete();

        $this->refreshPost($postId);

        // Reset properties
        $this->confirmingCommentDeletion = false;
        $this->commentIdToDelete = null;
    }
    public function loadMore()
    {
        if ($this->hasMorePages) {
            $this->page++;
            $this->loadPosts(false);
        }
    }

    public function toggleLike(Post $post)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        if ($post->isLikedBy($user)) {
            $post->likes()->where('user_id', $user->id)->delete();
        } else {
            $post->likes()->create(['user_id' => $user->id]);
        }
        $this->refreshPost($post->id);
    }

    public function toggleSave(Post $post)
    {
        // Placeholder for save functionality
    }

    public function toggleFollow(User $userToFollow)
    {
        if (!Auth::check()) {
            return;
        }
        $currentUser = Auth::user();

        if ($currentUser->id === $userToFollow->id) {
            return;
        }

        if ($currentUser->isFollowing($userToFollow)) {
            $currentUser->following()->detach($userToFollow->id);
        } else {
            $currentUser->following()->attach($userToFollow->id);
        }

        $this->posts = $this->posts->map(function ($p) use ($userToFollow) {
            if ($p->user_id === $userToFollow->id) {
            }
            return $p;
        });
    }

    public function addComment(Post $post)
    {
        if (!Auth::check()) {
            return;
        }

        $this->validate([
            "newCommentText.{$post->id}" => 'required|string|max:1000',
        ]);

        $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->newCommentText[$post->id],
        ]);

        $this->newCommentText[$post->id] = '';
        $this->openCommentsSection[$post->id] = true;
        $this->refreshPost($post->id);
    }

    public function toggleComments(Post $post)
    {
        $this->openCommentsSection[$post->id] = !($this->openCommentsSection[$post->id] ?? false);
        if ($this->openCommentsSection[$post->id] && $post->comments->isEmpty()) {
            $this->refreshPost($post->id);
        }
    }

    #[On('deletePost')]
    public function deletePost($postId)
    {
        $post = Post::findOrFail($postId);
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }
        $post->delete();
        $this->loadPosts();
        $this->js("
            showToast({
            type: 'success',
            title: 'Post Deleted!',
            message: ''
            })
        ");
    }
    #[On('postCreated')]
    public function prependPost($postId)

    {
        $post = Post::with([
            'user',
            'attachments',
            'comments' => fn($q) => $q->with('user')->orderBy('created_at', 'desc'),
            'likes'
        ])->find($postId);

        if ($post && !$this->posts->contains('id', $postId)) {
            $this->initializePostState($post);
            $this->posts->prepend($post);
        }
    }

    private function initializePostState(Post $post): void
    {
        if (!isset($this->newCommentText[$post->id])) {
            $this->newCommentText[$post->id] = '';
        }
        if (!isset($this->openCommentsSection[$post->id])) {
            $this->openCommentsSection[$post->id] = false;
        }
    }

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

    public function openImageModal($postId, $imageIndex)
    {
        $post = $this->posts->firstWhere('id', $postId);
        if (!$post || $post->attachments->where('file_type', 'image')->isEmpty()) {
            return;
        }

        $this->imageModalUrls = $post->attachments
            ->where('file_type', 'image')
            ->values()
            ->map(function ($attachment, $index) use ($postId) {
                return ['url' => $attachment->file_url, 'originalPostId' => $postId, 'originalIndex' => $index];
            })->all();

        $this->currentImageModalIndex = $imageIndex;
        $this->imageModalOpen = true;
        $this->dispatch('imageModalOpened');
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
