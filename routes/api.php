<?php

use App\Http\Controllers\Api\FollowerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SearchController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');





// Authentication Routes
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Protected Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');

    // User Profile Management
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('api.profile.update');
    Route::post('/profile/avatar', [UserController::class, 'updateAvatar'])->name('api.profile.avatar.update');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('api.profile.password.update');

    // User Management (General & Admin) - Keep general user routes separate if needed
    Route::get('/users', [UserController::class, 'index'])->name('api.users.index'); // List all users
    Route::get('/users/{user}', [UserController::class, 'show'])->name('api.users.show'); // Show specific user

    // Admin User Management Routes (Protect with middleware e.g., ->middleware('role:admin'))
    Route::prefix('admin')->name('api.admin.')->middleware('auth:sanctum')->group(function () { // Example role check can be done in controller
        Route::put('/users/{user}', [UserController::class, 'adminUpdateUser'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'adminDeleteUser'])->name('users.delete');
    });


    // Posts
    Route::post('/posts', [PostController::class, 'store'])->name('api.posts.store');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('api.posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('api.posts.destroy');
    // Attachments to posts
    Route::post('/posts/{post}/attachments', [PostController::class, 'addAttachment'])->name('api.posts.attachments.store');
    Route::delete('/posts/{post}/attachments/{attachment}', [PostController::class, 'removeAttachment'])->name('api.posts.attachments.destroy');


    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('api.posts.comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('api.comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('api.comments.destroy');


    // Likes
    Route::post('/posts/{post}/like', [LikeController::class, 'store'])->name('api.posts.like');
    Route::delete('/posts/{post}/unlike', [LikeController::class, 'destroy'])->name('api.posts.unlike');

    Route::middleware('auth:sanctum')->group(function () {
        // ... (existing protected user routes)
        Route::post('/users/{user}/follow', action: [FollowerController::class, 'follow'])->name('users.follow');
        Route::delete('/users/{user}/unfollow', action: [FollowerController::class, 'unfollow'])->name('users.unfollow');
    });
    Route::get('/users/{user}/following', [FollowerController::class, 'getFollowing'])->name('users.following');
    Route::get('/users/{user}/followers', [FollowerController::class, 'getFollowers'])->name('users.followers');
    // Messages
    Route::get('/messages/conversations', [MessageController::class, 'getConversations'])->name('api.messages.conversations');
    Route::get('/messages/with/{user}', [MessageController::class, 'getMessagesWithUser'])->name('api.messages.with_user');
    Route::post('/messages', [MessageController::class, 'sendMessage'])->name('api.messages.send');
    Route::put('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('api.messages.read');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('api.messages.destroy');
    Route::prefix('search')->name('api.search.')->group(function () {
        Route::get('/posts', [PostController::class, 'searchPosts'])->name('posts');
        Route::get('/users', [UserController::class, 'searchUsers'])->name('users');
        Route::get('/all', [SearchController::class, 'searchAll'])->name('all');
    });

        // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('api.notifications.index');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('api.notifications.markRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('api.notifications.markAllRead');


});

// Publicly Accessible Routes (No Authentication Needed)
Route::get('/posts', [PostController::class, 'index'])->name('api.posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('api.posts.show');
Route::get('/users/{user}/posts', [PostController::class, 'userPosts'])->name('api.users.posts');

Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('api.posts.comments.index');
Route::get('/comments/{comment}', [CommentController::class, 'show'])->name('api.comments.show'); // If direct access to a comment is needed

Route::get('/posts/{post}/liked-by', [LikeController::class, 'likedBy'])->name('api.posts.likedby'); // Get users who liked a post

// Fallback route for 404 API errors
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found.'
    ], 404);
});
