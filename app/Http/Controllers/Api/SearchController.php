<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SearchController extends ApiController
{

    /**
     * Search across multiple models (e.g., users and posts).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2|max:100',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray());
        }

        $query = $request->input('query');
        $perPage = $request->input('per_page', 10); // Allow customizing items per page for each type

        // Search Users
        $users = User::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('username', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%"); // Optional: search email if public or for admins
        })
            ->select(['id', 'name', 'username', 'avatar', 'bio']) // Select only necessary fields for preview
            ->take($perPage) // Take a limited number for combined results
            ->get();

        // Search Posts
        $posts = Post::where('content', 'LIKE', "%{$query}%")
            ->with(['user:id,name,username,avatar', 'attachments']) // Optimize user relationship loading
            ->withCount(['comments', 'likes'])
            ->latest()
            ->take($perPage) // Take a limited number for combined results
            ->get();

        $results = [
            'users' => $users,
            'posts' => $posts,
            // You can add other types here, e.g., 'comments', 'hashtags'
        ];

        if ($users->isEmpty() && $posts->isEmpty()) {
            return $this->sendResponse($results, 'No results found matching your query.');
        }

        return $this->sendResponse($results, 'Search results retrieved successfully.');
    }
}
