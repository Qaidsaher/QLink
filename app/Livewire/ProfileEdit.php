<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileEdit extends Component
{
    use WithFileUploads;

    public User $user;

    // Profile Info Properties
    public $name;
    public $username;
    public $bio;
    public $location;
    public $website;
    public $avatar;
    public $coverPhoto;

    // Notification Settings
    public $notify_new_follower;
    public $notify_new_comment;

    // Privacy Settings
    public $messages_from;

    // Password Update Properties
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    // Account Deletion Properties
    public $confirmingAccountDeletion = false;
    public $delete_password = '';

    // Username Live Validation State
    public string $usernameStatus = 'idle';

    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->username = $this->user->username;
        $this->bio = $this->user->bio;
        $this->location = $this->user->location;
        $this->website = $this->user->website;
        $this->notify_new_follower = $this->user->notify_new_follower ?? true;
        $this->notify_new_comment = $this->user->notify_new_comment ?? true;
        $this->messages_from = $this->user->messages_from ?? 'everyone';
    }

    public function updatedUsername(string $value)
    {
        if (strtolower($value) === strtolower($this->user->username)) {
            $this->usernameStatus = 'idle';
            $this->resetErrorBag('username');
            return;
        }
        $this->usernameStatus = 'checking';
        $this->validateOnly('username', ['username' => ['required', 'string', 'max:25', 'alpha_dash', Rule::unique('users')->ignore($this->user->id)]]);
        $this->usernameStatus = 'available';
    }

    public function updatedAvatar()
    {
        $this->validateOnly('avatar', ['avatar' => 'nullable|image|max:2048']);
    }
    public function updatedCoverPhoto()
    {
        $this->validateOnly('coverPhoto', ['coverPhoto' => 'nullable|image|max:5120']);
    }

    public function saveProfile()
    {
        $validatedData = $this->validate([
            'name' => 'required|string|max:50',
            'username' => ['required', 'string', 'max:25', 'alpha_dash', Rule::unique('users')->ignore($this->user->id)],
            'bio' => 'nullable|string|max:160',
            'location' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:100',
            'avatar' => 'nullable|image|max:2048',
            'coverPhoto' => 'nullable|image|max:5120',
        ]);

        if ($this->avatar) {
            $validatedData['avatar'] = $this->avatar->store('avatars', 'public');
        }
        if ($this->coverPhoto) {
            $validatedData['coverPhoto'] = $this->coverPhoto->store('covers', 'public');
           
        }

        $this->user->update($validatedData);
        $this->user->save();
        $this->dispatch('profile-saved');
    }

    public function saveNotificationSettings()
    {
        $validatedData = $this->validate([
            'notify_new_follower' => 'required|boolean',
            'notify_new_comment' => 'required|boolean',
        ]);

        $this->user->update($validatedData);
        $this->dispatch('notifications-saved');
    }

    public function savePrivacySettings()
    {
        $validatedData = $this->validate([
            'messages_from' => ['required', 'string', Rule::in(['everyone', 'following'])],
        ]);

        $this->user->update($validatedData);
        $this->dispatch('privacy-saved');
    }

    public function updatePassword()
    {
        $validatedData = $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'new_password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        $this->user->update(['password' => Hash::make($validatedData['new_password'])]);
        $this->reset('current_password', 'new_password', 'new_password_confirmation');
        $this->dispatch('password-updated');
    }

    public function confirmAccountDeletion()
    {
        $this->confirmingAccountDeletion = true;
    }

    public function deleteAccount()
    {
        $this->validate(['delete_password' => ['required', 'string', 'current_password']]);
        $user = $this->user;
        Auth::logout();

        if ($user->delete()) {
            session()->flash('success', 'Your account has been permanently deleted.');
            return redirect()->route('home');
        }
    }

    public function render()
    {
        return view('livewire.profile-edit');
    }
}
