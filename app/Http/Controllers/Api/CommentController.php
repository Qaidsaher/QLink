<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends ApiController
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum')->except(['index']);
    }

    /**
     * Display a listing of the comments for a post.
     */
    public function index(Post $post, Request $request)
    {
        $comments = $post->comments()
                         ->whereNull('parent_comment_id') // Get top-level comments
                         ->with(['user', 'replies.user']) // Eager load user and replies with their users
                         ->latest()
                         ->paginate(10); // Paginate top-level comments

        return $this->sendResponse($comments, 'Comments retrieved successfully.');
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:2000',
            'parent_comment_id' => 'nullable|exists:comments,id', // For replies
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray());
        }

        $commentData = [
            'content' => $request->content,
            'user_id' => Auth::id(),
        ];

        if ($request->filled('parent_comment_id')) {
             // Ensure parent comment belongs to the same post
            $parentComment = Comment::where('id', $request->parent_comment_id)
                                    ->where('post_id', $post->id)
                                    ->first();
            if (!$parentComment) {
                return $this->sendError('Parent comment not found or does not belong to this post.', [], 422);
            }
            $commentData['parent_comment_id'] = $request->parent_comment_id;
        }

        $comment = $post->comments()->create($commentData);
        $comment->load('user', 'replies.user'); // Load user and replies for the response

        return $this->sendResponse($comment, 'Comment posted successfully.');
    }

    /**
     * Display the specified comment.
     * Typically not needed if comments are listed under posts.
     * But if you want a direct link to a comment:
     */
    public function show(Comment $comment)
    {
        $comment->load('user', 'post', 'replies.user');
        return $this->sendResponse($comment, 'Comment retrieved successfully.');
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && Auth::user()->role !== 'admin' && Auth::user()->role !== 'moderator') {
            return $this->sendForbidden('You are not authorized to update this comment.');
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray());
        }

        $comment->content = $request->content;
        $comment->save();
        $comment->load('user', 'replies.user');

        return $this->sendResponse($comment, 'Comment updated successfully.');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && Auth::user()->role !== 'admin' && Auth::user()->role !== 'moderator') {
            return $this->sendForbidden('You are not authorized to delete this comment.');
        }
        // If the comment has replies, you might want to handle them (e.g., delete them, or mark content as "[deleted]")
        // For simplicity, cascade delete or manual deletion of replies is assumed if needed.
        $comment->delete();

        return $this->sendResponse([], 'Comment deleted successfully.');
    }
}
