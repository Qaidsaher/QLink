<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(string $username)
    {
        $user = User::where('username', $username)->firstOrFail(); // Use
        if (!$user->exists) { // Or simply rely on RMD to throw 404 if not found
            abort(404, 'User profile not found.');
        }

        // It's good practice to ensure the user object is fully loaded if it's just an ID from binding
        $user->refresh(); // This re-fetches the model from DB, might not be needed if RMD is complete

        $user->loadCount(['posts', 'followers', 'following']);

        // You could also pass the full user object or just the username
        return view('pages.profile.show', [
            'user' => $user // Pass the full User object
            // OR 'targetUsername' => $username // If Livewire should fetch the user
        ]);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
