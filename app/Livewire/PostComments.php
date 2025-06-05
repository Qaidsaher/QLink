<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PostComments extends Component
{
    use WithPagination;

    public Post $post;
    public string $newCommentContent = ''; // Renamed for clarity
    public ?int $replyToCommentId = null; // The ID of the comment being replied to
    public ?string $replyToUsername = null; // Username of the person being replied to

    public int $perPage = 5; // Comments per page

    protected function rules()
    {
        return [
            'newCommentContent' => 'required|string|min:1|max:2000',
        ];
    }

    protected $messages = [
        'newCommentContent.required' => 'The comment field cannot be empty.',
        'newCommentContent.min' => 'The comment must be at least 1 character.',
        'newCommentContent.max' => 'The comment may not be greater than 2000 characters.',
    ];

    // Listen for events that might affect comments, e.g., if a comment is deleted elsewhere
    protected $listeners = ['commentDeleted' => '$refresh', 'commentAddedFromOutside' => '$refresh'];

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function updatedNewCommentContent()
    {
        $this->validateOnly('newCommentContent');
    }

    public function addComment()
    {
        if (!Auth::check()) {
            session()->flash('toast-error', 'You must be logged in to comment.');
            return redirect()->route('login');
        }

        $this->validate();

        $comment = $this->post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->newCommentContent,
            'parent_comment_id' => $this->replyToCommentId,
        ]);

        $this->resetForm();
        $this->goToPage(1, 'commentsPage'); // Go to first page to see the new comment

        // Emit event to update comment count on the PostCard if it's listening
        $this->emitTo('post-card', 'commentAddedToPost', $this->post->id);
        $this->emit('commentAddedGlobal', $this->post->id, $comment->id); // For any other listeners

        session()->flash('toast-message', $this->replyToCommentId ? 'Reply posted!' : 'Comment posted!');
    }

    public function setReplyTo(Comment $comment)
    {
        $this->replyToCommentId = $comment->id;
        $this->replyToUsername = $comment->user->username;
        // Dispatch browser event to focus the textarea (requires Alpine.js or custom JS)
        $this->dispatchBrowserEvent('focus-comment-input');
    }

    public function cancelReply()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->newCommentContent = '';
        $this->replyToCommentId = null;
        $this->replyToUsername = null;
        $this->resetValidation(); // Clear validation errors
    }

    public function loadMoreComments()
    {
        // This is handled by Livewire's pagination if using $this->comments->links()
        // If you implement custom "load more" button, you'd increment $perPage or page number.
        // For now, we'll use standard pagination links.
    }

    public function render()
    {
        $comments = $this->post->comments()
            ->whereNull('parent_comment_id') // Get only top-level comments
            ->with([
                'user', // Eager load comment author
                'replies' => function ($query) { // Eager load first level of replies
                    $query->with('user')
                          ->withCount('replies as sub_replies_count') // Count of replies to this reply
                          ->latest();
                },
                'replies.replies' => function ($query){ // Eager load second level - adjust depth as needed
                    $query->with('user')->latest();
                }
            ])
            ->withCount('replies') // Count of direct replies to this top-level comment
            ->latest() // Show newest comments first
            ->paginate($this->perPage, ['*'], 'commentsPage'); // Unique paginator name

        return view('livewire.post-comments', [
            'comments' => $comments,
        ]);
    }
}
