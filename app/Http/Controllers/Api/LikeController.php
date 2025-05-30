<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends ApiController
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    // }

    /**
     * Like a post.
     */
    public function store(Request $request, Post $post)
    {
        $user = Auth::user();

        // Check if already liked
        $existingLike = $post->likes()->where('user_id', $user->id)->first();
        if ($existingLike) {
            return $this->sendError('You have already liked this post.', [], 409); // 409 Conflict
        }

        $like = $post->likes()->create(['user_id' => $user->id]);
        $post->loadCount('likes'); // Recalculate likes count

        return $this->sendResponse(
            ['like' => $like, 'likes_count' => $post->likes_count],
            'Post liked successfully.'
        );
    }

    /**
     * Unlike a post.
     */
    public function destroy(Request $request, Post $post)
    {
        $user = Auth::user();
        $like = $post->likes()->where('user_id', $user->id)->first();

        if (!$like) {
            return $this->sendError('You have not liked this post.', [], 404);
        }

        $like->delete();
        $post->loadCount('likes'); // Recalculate likes count

        return $this->sendResponse(
            ['likes_count' => $post->likes_count],
            'Post unliked successfully.'
        );
    }

    /**
     * Get users who liked a post.
     */
    public function likedBy(Post $post)
    {
        $usersWhoLiked = $post->likes()->with('user')->paginate(15);
        return $this->sendResponse($usersWhoLiked, 'Users who liked the post retrieved.');
    }
}
