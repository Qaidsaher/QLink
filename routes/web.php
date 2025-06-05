<?php

use App\Http\Controllers\ProfileController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Publicly accessible home/feed (can be same for now)
Route::get('/', function () {
    return view('pages.feed'); // Points to a Blade view that will host Livewire
})->name('home');

Route::get('/feed', function () {
    return view('pages.feed');
})->name('feed');

// Placeholder for create post page
Route::get('/posts/create', function () {
    return view('pages.create-post');
})->middleware(['auth'])->name('posts.create');

// Placeholder for single post page
Route::get('/posts/{post:id}', function (Post $post) { // Use {post:id} for clarity if not using slugs
    // Eager load necessary relationships for the main post to avoid N+1 issues on this page
    $post->load(['user', 'attachments', 'likes', 'comments' => function ($query) {
        // Optionally, you could load some initial comments here, but PostComments component will handle its own pagination
        $query->with('user')->whereNull('parent_comment_id')->latest()->take(5); // Example
    }]);
    $post->loadCount(['comments', 'likes']); // Ensure counts are loaded

    return view('pages.posts.show', ['post' => $post]);
})->name('posts.show');

// Placeholder for search page
Route::get('/search', function () {
    return view('pages.search');
})->name('search.page');

// Profile page using username for route model binding
Route::get('/{user:username}', function (User $user) {
    return view('pages.profile.show', ['user' => $user]);
})->name('profile.show');

// Edit profile page for authenticated user
Route::get('/profile/settings/edit', function () { // Changed route slightly for clarity
    return view('pages.profile.edit', ['user' => Auth()->user]);
})->middleware(['auth'])->name('profile.edit');












// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
