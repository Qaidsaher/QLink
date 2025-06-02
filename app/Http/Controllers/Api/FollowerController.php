<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends ApiController
{
    /**
     * Allow the authenticated user to follow another user.
     *
     * @param  \App\Models\User  $user The user to follow
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow(User $user)
    {
        $authUser = Auth::user();

        if ($authUser->id == $user->id) {
            return $this->sendError('You cannot follow yourself.', [], 422);
        }

        // Check if already following
        if ($authUser->following()->where('following_id', $user->id)->exists()) {
            return $this->sendError('You are already following this user.', [], 409); // Conflict
        }

        $authUser->following()->attach($user->id);

        // Optionally, return updated follower counts for both users or just a success message
        return $this->sendResponse([
            'message' => 'Successfully followed user.',
            // 'followers_count' => $user->fresh()->followers_count // get fresh count for the followed user
        ], 'Successfully followed user.');
    }

    /**
     * Allow the authenticated user to unfollow another user.
     *
     * @param  \App\Models\User  $user The user to unfollow
     * @return \Illuminate\Http\JsonResponse
     */
    public function unfollow(User $user)
    {
        $authUser = Auth::user();

        if (!$authUser->following()->where('following_id', $user->id)->exists()) {
            return $this->sendError('You are not following this user.', [], 404);
        }

        $authUser->following()->detach($user->id);

        return $this->sendResponse([
            'message' => 'Successfully unfollowed user.',
            // 'followers_count' => $user->fresh()->followers_count
        ], 'Successfully unfollowed user.');
    }

    /**
     * Get the list of users that a given user is following.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFollowing(User $user)
    {
        $following = $user->following()->select(['users.id', 'users.name', 'users.username', 'users.avatar'])->paginate(15);
        return $this->sendResponse($following, 'Following list retrieved successfully.');
    }

    /**
     * Get the list of users that are following a given user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFollowers(User $user)
    {
        $followers = $user->followers()->select(['users.id', 'users.name', 'users.username', 'users.avatar'])->paginate(15);
        return $this->sendResponse($followers, 'Followers list retrieved successfully.');
    }

    // When showing a user profile (UserController@show or a dedicated UserResource):
    // Make sure 'is_followed_by_auth_user', 'followers_count', 'following_count'
    // are included in the response. If you added them to $appends in User model, they should be.
    // Example UserController@show
    public function show(User $user) // Ensure your existing show method includes these
    {
        // The $appends in User model should automatically add these.
        // If not, you might explicitly load them or add them in a UserResource.
        // $user->loadCount(['followers', 'following']); // Already handled by accessor if in $appends
        // $user->is_followed_by_auth_user = $user->getIsFollowedByAuthUserAttribute(); // Accessor will do this
        return $this->sendResponse($user, 'User retrieved successfully.');
    }
}
