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

use Illuminate\Support\Facades\Auth;
use App\Livewire\ShowPost;
use App\Livewire\Search;
use App\Livewire\Posts;

use App\Livewire\Pages\AboutPage;
use App\Livewire\Pages\TermsPage;
use App\Livewire\Pages\PrivacyPage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', function () {
    $posts = Post::all();
    $users = User::all();

    $urls = [
        url('/'), // homepage
        url('/about'),
        url('/terms-of-service'),
        url('/privacy-policy'),
        url('/feed'),
    ];

    // Add posts URLs
    foreach ($posts as $post) {
        $urls[] = url("/posts/{$post->id}");
    }

    // Add user profile URLs
    foreach ($users as $user) {
        $urls[] = url("/user/{$user->username}");
    }

    $content = '<?xml version="1.0" encoding="UTF-8"?>';
    $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach ($urls as $url) {
        $content .= '<url>';
        $content .= '<loc>' . $url . '</loc>';
        $content .= '<changefreq>weekly</changefreq>';
        $content .= '<priority>0.8</priority>';
        $content .= '</url>';
    }

    $content .= '</urlset>';

    return response($content, 200)
        ->header('Content-Type', 'application/xml');
});

Route::get('/services/start', function (Request $request) {
    $password = $request->query('password');
    if ($password !== '0000000') {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    try {
        // Start Queue
        shell_exec('php ' . base_path('artisan') . ' queue:restart');
        shell_exec('php ' . base_path('artisan') . ' queue:work --daemon --tries=3 > /dev/null 2>&1 &');

        // Start Laravel Echo Server
        shell_exec("cd /path-to-project && nohup laravel-echo-server start > /dev/null 2>&1 &");

        // Start Reverb
        shell_exec('php ' . base_path('artisan') . ' reverb:start > /dev/null 2>&1 &');

        return response()->json([
            'success' => true,
            'message' => 'All services started.',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to start services.',
            'error' => $e->getMessage(),
        ], 500);
    }
});
Route::get('/services/stop', function (Request $request) {
    $password = $request->query('password');
    if ($password !== '0000000') {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    try {
        // Stop Queue workers (kill all queue:work processes)
        shell_exec("pkill -f 'php artisan queue:work'");

        // Stop Laravel Echo Server (kill by process name)
        shell_exec("pkill -f 'laravel-echo-server'");

        // Stop Reverb (if available)
        shell_exec("php " . base_path('artisan') . " reverb:restart --stop");
        // or if you have a specific command to stop it, replace this with correct command.
        // Or kill its process if no stop command:
        shell_exec("pkill -f 'php artisan reverb:start'");

        return response()->json([
            'success' => true,
            'message' => 'All services stopped.',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to stop services.',
            'error' => $e->getMessage(),
        ], 500);
    }
});

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
            'username' => $googleUser->getName() . '_' . $googleUser->getId(),
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
