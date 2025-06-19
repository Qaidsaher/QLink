<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Attributes\Title;

#[Title('show profile of :user.name')]

class UserProfile extends Component
{
    use WithPagination;

    public User $user; // Will be hydrated by Livewire
    public string $activeTab = 'posts';
    public bool $isFollowingThisUser = false;
    // No profileCompletionStatus for this simpler version

    public array $openCommentsSection = [];
    public array $newCommentText = [];

    protected $paginationTheme = 'tailwind';

    // protected $listeners = [
    //     'postCreatedFromProfile' => '$refresh',
    //     'commentAddedToProfilePost' => '$refresh',
    //     'likeToggledOnProfilePost' => '$refresh',
    //     'followStatusChanged' => 'handleFollowStatusChange',
    // ];

    public function mount(User $user)
    {
        // The user model is passed with counts already loaded by ProfilePageController
        $this->user = $user;
        $this->updateIsFollowingStatus();
    }

    public function hydrate()
    {
        // Re-check follow status on subsequent requests
        $this->updateIsFollowingStatus();

        // Ensure counts are loaded if they somehow weren't (defensive)
        // The parent controller's loadCount should generally suffice.
        if (!$this->user->relationLoaded('posts_count')) {
            $this->user->loadCount(['posts', 'followers', 'following']);
        }
    }

    public function updateIsFollowingStatus()
    {
        if (Auth::check() && Auth::id() !== $this->user->id) {
            $this->isFollowingThisUser = Auth::user()->isFollowing($this->user);
        } else {
            $this->isFollowingThisUser = false;
        }
    }

    #[On('followStatusChanged')]

    public function handleFollowStatusChange()
    {


        $this->user->loadCount(['followers', 'following']); // Refresh counts

    }

    public function toggleFollow()
    {
        if (!Auth::check()) {
            return $this->dispatch('notify', ['message' => 'Please login to follow users.', 'type' => 'info']);
        }
        $currentUser = Auth::user();
        if ($currentUser->id === $this->user->id) return;

        if ($this->isFollowingThisUser) {
            $currentUser->following()->detach($this->user->id);
        } else {
            $currentUser->following()->attach($this->user->id);
        }
        $this->isFollowingThisUser = !$this->isFollowingThisUser;
        $this->user->loadCount(['followers', 'following']); // Crucial: Refresh counts on the $user object

        $this->dispatch('followStatusChanged', userId: $this->user->id, isFollowing: $this->isFollowingThisUser);
    }

    public function switchTab(string $tabName)
    {
        $this->activeTab = $tabName;
        $this->resetPage('userProfilePostsPage');
        $this->resetPage('followersPage');
        $this->resetPage('followingPage');
    }

    // --- Computed Properties for Tab Content ---
    public function getUserPostsProperty()
    {
        return Post::where('user_id', $this->user->id)
            ->with([
                'user:id,name,username,avatar', // Use 'avatar' (path)
                'attachments',
                'likes', // For Post model's is_liked and likes_count accessors
                'comments' => function ($query) {
                    $query->with('user:id,name,username,avatar')->orderBy('created_at', 'desc');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'userProfilePostsPage');
    }

    public function getFollowersProperty()
    {
        return $this->user->followers()
            ->select('users.id', 'users.name', 'users.username', 'users.avatar', 'users.bio') // Select only needed fields
            ->paginate(15, ['*'], 'followersPage');
    }

    public function getFollowingProperty()
    {
        return $this->user->following()
            ->select('users.id', 'users.name', 'users.username', 'users.avatar', 'users.bio')
            ->paginate(15, ['*'], 'followingPage');
    }

    // --- Simplified Post Interaction Methods ---
    public function toggleLike(int $postId)
    {
        if (!Auth::check()) {
            return $this->dispatch('notify', ['message' => 'Please login to like posts.', 'type' => 'info']);
        }
        $post = Post::find($postId);
        if (!$post || $post->user_id !== $this->user->id) return;

        $authUser = Auth::user();
        if ($post->isLikedBy($authUser)) { // Assuming Post model has isLikedBy()
            $post->likes()->where('user_id', $authUser->id)->delete();
        } else {
            $post->likes()->create(['user_id' => $authUser->id]);
        }
    }

    public function toggleCommentsSection(int $postId)
    {
        $this->openCommentsSection[$postId] = !($this->openCommentsSection[$postId] ?? false);
        if ($this->openCommentsSection[$postId] && !isset($this->newCommentText[$postId])) {
            $this->newCommentText[$postId] = '';
        }
    }

    public function addComment(int $postId)
    {
        if (!Auth::check()) {
            return $this->dispatch('notify', ['message' => 'Please login to comment.', 'type' => 'info']);
        }
        $commentText = trim($this->newCommentText[$postId] ?? '');
        if (empty($commentText)) {
            return;
        }
        $post = Post::find($postId);
        if (!$post || $post->user_id !== $this->user->id) return;

        $post->comments()->create(['user_id' => Auth::id(), 'content' => $commentText]);
        $this->newCommentText[$postId] = '';
        $this->openCommentsSection[$postId] = true;
    }

    public function render()
    {
        return view('livewire.user-profile', [
            'postsForProfile' => ($this->activeTab === 'posts') ? $this->userPosts : new EloquentCollection(),
            'followersList'   => ($this->activeTab === 'followers') ? $this->followers : new EloquentCollection(),
            'followingList'   => ($this->activeTab === 'following') ? $this->following : new EloquentCollection(),
        ]);
    }
}
