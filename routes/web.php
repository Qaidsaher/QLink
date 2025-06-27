<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Chat;
use App\Livewire\Conversations;
use App\Livewire\Feed;
use App\Livewire\Notification;
use App\Livewire\PostCreate;
use App\Livewire\PostUpdate;
use App\Livewire\ProfileEdit;
use App\Livewire\UserProfile;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\ShowPost;
use App\Livewire\Search;
use App\Livewire\Posts;

use App\Livewire\Pages\AboutPage;
use App\Livewire\Pages\TermsPage;
use App\Livewire\Pages\PrivacyPage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

// Redirect to Google
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

// Handle the callback
Route::get('/auth/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();
    // Find or create the user
    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'username' => $googleUser->getName().'_'.$googleUser->getId(),
            'google_id' => $googleUser->getId(),
            'google_token' => $googleUser->token,
            'google_refresh_token' => $googleUser->refreshToken,
            'avatar' => $googleUser->getAvatar(),
            'password' => Hash::make(value: Str::random(16)),
            'role' => 'user',
            'bio' => 'New user from Google',
            'notify_new_follower' => true,
            'notify_new_comment' => true,
            'messages_from' => 'everyone',
        ]
    );

    Auth::login($user);

    return redirect('/feed'); // or any route you want after login
})->name('google.callback');

// Static Pages using Livewire Components
Route::get('/about', AboutPage::class)->name('about');
Route::get('/terms-of-service', TermsPage::class)->name('terms');
Route::get('/privacy-policy', PrivacyPage::class)->name('privacy');


Route::middleware('auth')->group(function () {

    Route::get('/posts/create', PostCreate::class)->name('posts.create');
    Route::get('/posts/{post}/edit', action: PostUpdate::class)->name('posts.edit');
    Route::get('/messages/conversations/{user?}', action: Conversations::class)->name('messages');
    Route::get('/messages/chat/{user?}', action: Chat::class)->name('chat');
    Route::get('/notifications', Notification::class)->name(name: 'notifications');
    Route::get('/profile/edit', ProfileEdit::class)->name('profile.edit');
});

require __DIR__ . '/auth.php';
// Publicly accessible home/feed (can be same for now)
Route::get('/', Feed::class)->name('home');

Route::get('/feed', action: Feed::class)->name('feed');

// Placeholder for create post page
// Route::get('/posts/create', function () {
//     return view('pages.create-post');
// })->middleware(['auth'])->name('posts.create');

// Placeholder for single post page
Route::get('/posts/{post:id}', action: ShowPost::class)->name('posts.show');

// Placeholder for search page
Route::get('/search', Search::class)->name('search.index');


// Profile page using username for route model binding
Route::get('/user/{user:username}', UserProfile::class)->name('profile.show');


// Edit profile page for authenticated user
// Route::get('/profile/settings/edit', function () { // Changed route slightly for clarity
//     return view('pages.profile.edit', ['user' => Auth::user()]);
// })->middleware(['auth'])->name('profile.edit');


// Route::view('/messages', 'pages.messages')->middleware('auth')->name('messages');










Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
