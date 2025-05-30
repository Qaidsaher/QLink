<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Add pagination, search, filtering as needed
        $users = User::paginate(15);
        return $this->sendResponse($users, 'Users retrieved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->loadCount(['posts', 'comments', 'likes']); // Example of loading counts
        return $this->sendResponse($user, 'User retrieved successfully.');
    }

    /**
     * Update the authenticated user's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'username' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'bio' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user->fill($request->only(['name', 'username', 'email', 'bio']));
        $user->save();

        return $this->sendResponse($user, 'Profile updated successfully.');
    }

    /**
     * Update the authenticated user's avatar.
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        return $this->sendResponse(['avatar_url' => Storage::url($path)], 'Avatar updated successfully.');
    }


    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => ['required', 'string', \Illuminate\Validation\Rules\Password::defaults(), 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError('Current password does not match.', [], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->sendResponse([], 'Password updated successfully.');
    }

    /**
     * (Admin) Update user details.
     */
    public function adminUpdateUser(Request $request, User $user)
    {
        // Implement authorization check for admin
        if (Auth::user()->role !== 'admin') {
            return $this->sendForbidden('Only admins can perform this action.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'username' => [
                'sometimes', 'required', 'string', 'max:255', Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id),
            ],
            'bio' => 'nullable|string|max:1000',
            'role' => ['sometimes', 'required', Rule::in(['user', 'moderator', 'admin'])],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user->fill($request->only(['name', 'username', 'email', 'bio', 'role']));
        $user->save();

        return $this->sendResponse($user, 'User updated successfully by admin.');
    }

    /**
     * (Admin) Delete a user.
     */
    public function adminDeleteUser(User $user)
    {
        // Implement authorization check for admin
        if (Auth::user()->role !== 'admin') {
            return $this->sendForbidden('Only admins can perform this action.');
        }
        if ($user->id === Auth::id()) {
             return $this->sendError('Admin cannot delete their own account this way.', [], 403);
        }

        $user->delete(); // Consider soft deletes if needed
        return $this->sendResponse([], 'User deleted successfully by admin.');
    }
}
