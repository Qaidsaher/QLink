<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class PostAction extends Component
{
    public Post $post;
    public bool $openComments = false;
    public string $newCommentText = '';

    // New properties for the delete confirmation modal
    public ?int $commentToDeleteId = null;
    public bool $confirmingCommentDeletion = false;

    protected $listeners = ['refreshPost' => 'refreshPostData'];

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function toggleComments()
    {
        $this->openComments = !$this->openComments;
        if ($this->openComments) {
            $this->refreshPostData();
        }
    }

    public function addComment()
    {
        if (!Auth::check()) return;

        $this->validate(['newCommentText' => 'required|string|max:1000']);

        $this->post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->newCommentText,
        ]);

        $this->newCommentText = '';
        $this->refreshPostData();

        $this->js("
            showToast({ 
                type: 'success', 
                title: 'Commit Created!', 
                message: 'Your new commit is now live for everyone to see.' 
            })
        ");
    }

    /**
     * Step 1: When delete is clicked, ask for confirmation.
     */
    public function requestDeleteConfirmation(int $commentId)
    {
        $this->commentToDeleteId = $commentId;
        $this->confirmingCommentDeletion = true;
    }
    
    /**
     * Step 2: If confirmed, delete the comment.
     */
    public function deleteComment()
    {
        if (is_null($this->commentToDeleteId)) return;

        $comment = Comment::find($this->commentToDeleteId);

        if ($comment && Auth::id() === $comment->user_id) {
            $comment->delete();
        }

        $this->confirmingCommentDeletion = false;
        $this->commentToDeleteId = null;
        $this->refreshPostData();
        $this->js("
            showToast({ 
            type: 'success', 
            title: 'Comment Deleted!', 
            message: 'The comment has been successfully removed.' 
            })
        ");
        
    }
    
    public function cancelDelete()
    {
        $this->confirmingCommentDeletion = false;
        $this->commentToDeleteId = null;
    }

    public function toggleLike()
    {
        if (!Auth::check()) return;

        $user = Auth::user();

        if ($this->post->isLikedBy($user)) {
            $this->post->likes()->where('user_id', $user->id)->delete();
        } else {
            $this->post->likes()->create(['user_id' => $user->id]);
        }
        $this->refreshPostData();
    }

    public function refreshPostData()
    {
        $this->post->load(['comments.user', 'likes'])->loadCount(['comments', 'likes']);
    }

    public function render()
    {
        return view('livewire.post-action');
    }
}

