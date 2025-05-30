<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\User;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends ApiController
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum')->except(['index', 'show', 'userPosts']);
    // }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Add search, filtering by tags, etc. later
        $posts = Post::with(['user', 'attachments', 'comments.user', 'likes'])
                     ->withCount(['comments', 'likes'])
                     ->latest()
                     ->paginate(15);
        return $this->sendResponse($posts, 'Posts retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,pdf,doc,docx|max:20480', // Max 20MB per file
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray());
        }

        $post = Auth::user()->posts()->create(['content' => $request->content]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('post_attachments', 'public');
                $post->attachments()->create([
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                ]);
            }
        }
        $post->load(['user', 'attachments', 'comments', 'likes']); // Reload with relations
        $post->loadCount(['comments', 'likes']);
        return $this->sendResponse($post, 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'attachments', 'comments.user', 'likes.user'])
             ->loadCount(['comments', 'likes']);
        return $this->sendResponse($post, 'Post retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if (Auth::id() !== $post->user_id && Auth::user()->role !== 'admin' && Auth::user()->role !== 'moderator') {
            return $this->sendForbidden('You are not authorized to update this post.');
        }

        $validator = Validator::make($request->all(), [
            'content' => 'sometimes|required|string',
            // Add logic for updating/removing attachments if needed
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray());
        }

        if ($request->has('content')) {
            $post->content = $request->content;
        }
        $post->save();

        $post->load(['user', 'attachments', 'comments', 'likes']);
        $post->loadCount(['comments', 'likes']);
        return $this->sendResponse($post, 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->user_id && Auth::user()->role !== 'admin' && Auth::user()->role !== 'moderator') {
            return $this->sendForbidden('You are not authorized to delete this post.');
        }

        // Delete attachments from storage
        foreach ($post->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }
        // Attachments and other related records (comments, likes) will be deleted by cascade if set up in DB.
        // Otherwise, delete them manually: $post->attachments()->delete(); $post->comments()->delete(); $post->likes()->delete();
        $post->delete();

        return $this->sendResponse([], 'Post deleted successfully.');
    }

    /**
     * Get posts by a specific user.
     */
    public function userPosts(User $user)
    {
        $posts = $user->posts()
                      ->with(['user', 'attachments', 'comments.user', 'likes'])
                      ->withCount(['comments', 'likes'])
                      ->latest()
                      ->paginate(15);
        return $this->sendResponse($posts, 'User posts retrieved successfully.');
    }

    /**
     * Add an attachment to an existing post.
     */
    public function addAttachment(Request $request, Post $post)
    {
        if (Auth::id() !== $post->user_id && Auth::user()->role !== 'admin' && Auth::user()->role !== 'moderator') {
            return $this->sendForbidden('You are not authorized to modify this post.');
        }

        $validator = Validator::make($request->all(), [
            'attachment' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,pdf,doc,docx|max:20480',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray());
        }

        $file = $request->file('attachment');
        $path = $file->store('post_attachments', 'public');
        $attachment = $post->attachments()->create([
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
        ]);
        $attachment->load('post'); // Optionally load the post relationship

        return $this->sendResponse($attachment, 'Attachment added successfully.');
    }

    /**
     * Remove an attachment from a post.
     */
    public function removeAttachment(Post $post, Attachment $attachment)
    {
        if (Auth::id() !== $post->user_id && Auth::user()->role !== 'admin' && Auth::user()->role !== 'moderator') {
            return $this->sendForbidden('You are not authorized to modify this post.');
        }

        if ($attachment->post_id !== $post->id) {
            return $this->sendError('Attachment does not belong to this post.', [], 422);
        }

        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        $attachment->delete();

        return $this->sendResponse([], 'Attachment removed successfully.');
    }
}
