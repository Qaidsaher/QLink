<?php

namespace App\Livewire\Profile;

use App\Models\User;
use App\Models\Post; // Assuming your Post model
// use App\Models\Notification; // Placeholder for a Notification model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // For debugging
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class EditForm extends Component
{
    use WithFileUploads, WithPagination;

    public User $user;
    public string $activeTab = 'edit_profile'; // Default tab

    // Form properties for 'edit_profile' tab
    public $name;
    public $username;
    public $email;
    public $bio;
    public $location; // Assuming these exist on your User model or a related profile model
    public $website;
    public $new_avatar; // TemporaryUploadedFile instance
    public $new_cover_photo; // TemporaryUploadedFile instance

    // Password change properties for 'account_settings' tab
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    // Notification settings example for 'notifications_settings' tab
    public bool $notifyOnNewFollower = true;
    public bool $notifyOnPostLike = true;
    public bool $notifyOnComment = true;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->user = Auth::user()->loadCount(['posts', 'followers', 'following']);
        $this->resetFormFields(); // Initialize form fields with user data

        // Example: Load existing notification preferences from user settings
        // This requires a mechanism to store/retrieve these (e.g., a JSON column or related table)
        // $userSettings = $this->user->settings ?? []; // Assuming 'settings' is a casted JSON attribute or relation
        // $this->notifyOnNewFollower = $userSettings['notify_on_new_follower'] ?? true;
        // $this->notifyOnPostLike = $userSettings['notify_on_post_like'] ?? true;
        // $this->notifyOnComment = $userSettings['notify_on_comment'] ?? true;
    }

    public function resetFormFields()
    {
        $this->name = $this->user->name;
        $this->username = $this->user->username;
        $this->email = $this->user->email;
        $this->bio = $this->user->bio;
        $this->location = $this->user->location ?? ''; // Use null coalescing for optional fields
        $this->website = $this->user->website ?? '';

        $this->new_avatar = null;
        $this->new_cover_photo = null;

        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
        $this->resetValidation(); // Clear any previous validation errors
    }

    // --- Validation Rule Methods ---

    protected function profileRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username,' . $this->user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
            'bio' => ['nullable', 'string', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'url:http,https', 'max:255'],
        ];
    }

    protected function avatarRules(): array
    {
        return [
            'new_avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'], // 2MB Max
        ];
    }

    protected function coverPhotoRules(): array
    {
        return [
            'new_cover_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB Max
        ];
    }

    protected function passwordRules(): array
    {
        return [
            'current_password' => [
                'required_with:new_password',
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && !Hash::check($value, Auth::user()->password)) {
                        $fail('The provided current password does not match your records.');
                    }
                }
            ],
            'new_password' => [
                'nullable',
                'required_with:current_password',
                'string',
                Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(),
                'confirmed'
            ],
            'new_password_confirmation' => ['nullable', 'required_with:new_password', 'string'],
        ];
    }

    // --- Lifecycle Hooks for Real-time File Validation ---

    public function updatedNewAvatar()
    {
        $this->validateOnly('new_avatar', $this->avatarRules());
    }

    public function updatedNewCoverPhoto()
    {
        $this->validateOnly('new_cover_photo', $this->coverPhotoRules());
    }

    // --- Action Methods ---

    public function saveProfileInformation()
    {
        $validatedData = $this->validate($this->profileRules());

        $this->user->name = $validatedData['name'];
        $this->user->username = $validatedData['username'];
        $this->user->email = $validatedData['email'];
        $this->user->bio = $validatedData['bio'];
        // $this->user->location = $validatedData['location'];
        // $this->user->website = $validatedData['website'];

        if ($this->new_avatar) {
            $this->validate($this->avatarRules()); // Validate again before storing
            // Delete old avatar if it exists and is not the default placeholder
            if ($this->user->avatar && $this->user->avatarUrl() !== User::defaultAvatarUrlPlaceholder($this->user->name)) {
                Storage::disk('public')->delete($this->user->avatar);
            }
            $this->user->avatar = $this->new_avatar->store('avatars', 'public');
        }

        if ($this->new_cover_photo) {
            $this->validate($this->coverPhotoRules()); // Validate again
            if ($this->user->cover_photo_path) { // Assuming 'cover_photo_path' is the DB column
                Storage::disk('public')->delete($this->user->cover_photo_path);
            }
            $this->user->cover_photo_path = $this->new_cover_photo->store('covers', 'public');
        }

        if ($this->user->isDirty()) { // Check if any attribute actually changed
            $this->user->save();
            $this->dispatch('notify', ['message' => 'Profile information updated!', 'type' => 'success']);
            $this->user = $this->user->fresh()->loadCount(['posts', 'followers', 'following']); // Refresh user data
            // Reset form fields AFTER saving, including temporary file uploads
            
            $this->resetFormFields();
        } else {
            // If nothing changed but new files were staged, they will be cleared by resetFormFields
            $this->new_avatar = null;
            $this->new_cover_photo = null;
            $this->dispatch('notify', ['message' => 'No changes to save in profile information.', 'type' => 'info']);
        }
    }

    public function updatePassword()
    {
        if (empty($this->current_password) && empty($this->new_password) && empty($this->new_password_confirmation)) {
            return; // No attempt to change password
        }

        $validatedData = $this->validate($this->passwordRules());

        $this->user->password = Hash::make($validatedData['new_password']);
        $this->user->save();

        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
        $this->resetValidation(['current_password', 'new_password', 'new_password_confirmation']);

        $this->dispatch('notify', ['message' => 'Password updated successfully!', 'type' => 'success']);
    }

    public function removeAvatar()
    {
        if ($this->user->avatar && $this->user->avatarUrlAttribute() !== User::defaultAvatarUrlPlaceholder($this->user->name)) {
            Storage::disk('public')->delete($this->user->avatar);
            $this->user->avatar = null;
            $this->user->save();
            $this->dispatch('notify', ['message' => 'Avatar removed.', 'type' => 'info']);
            $this->user = $this->user->fresh()->loadCount(['posts', 'followers', 'following']);
        }
    }

    public function removeCoverPhoto()
    {
        if ($this->user->cover_photo_path) {
            Storage::disk('public')->delete($this->user->cover_photo_path);
            $this->user->cover_photo_path = null;
            $this->user->save();
            $this->dispatch('notify', ['message' => 'Cover photo removed.', 'type' => 'info']);
            $this->user = $this->user->fresh()->loadCount(['posts', 'followers', 'following']);
        }
    }

    public function saveNotificationSettings()
    {
        // This is where you'd persist the notification settings.
        // For example, if 'settings' is a JSON column on the User model:
        // $currentSettings = $this->user->settings ?? [];
        // $currentSettings['notifications']['on_new_follower'] = $this->notifyOnNewFollower;
        // $currentSettings['notifications']['on_post_like'] = $this->notifyOnPostLike;
        // $currentSettings['notifications']['on_comment'] = $this->notifyOnComment;
        // $this->user->settings = $currentSettings;
        // $this->user->save();
        // Log::info('Notification settings updated for user: ' . $this->user->id, $currentSettings['notifications']);

        $this->dispatch('notify', ['message' => 'Notification settings saved!', 'type' => 'success']);
    }

    // --- Tab Switching and Data Fetching for Tabs ---
    public function switchTab(string $tabName)
    {
        $this->activeTab = $tabName;
        $this->resetPage('myPostsPage');
        $this->resetPage('myFollowersPage');
        $this->resetPage('myFollowingPage');
        $this->resetPage('myNotificationsPage'); // Assuming notifications will be paginated

        if ($tabName === 'edit_profile') {
            $this->resetFormFields(); // Refresh form data if switching back to edit profile
        }
    }

    public function getMyPostsProperty()
    {
        return Post::where('user_id', $this->user->id)
            ->with(['user:id,name,username,avatar', 'attachments', 'likes', 'comments.user:id,name,username,avatar'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'myPostsPage');
    }

    public function getMyFollowersProperty()
    {
        return $this->user->followers()
            ->select('users.id', 'users.name', 'users.username', 'users.avatar', 'users.bio')
            ->paginate(10, ['*'], 'myFollowersPage');
    }

    public function getMyFollowingProperty()
    {
        return $this->user->following()
            ->select('users.id', 'users.name', 'users.username', 'users.avatar', 'users.bio')
            ->paginate(10, ['*'], 'myFollowingPage');
    }

    public function getMyNotificationsProperty()
    {
        // Replace with your actual Notification model and logic
        // For now, returning an empty collection.
        // Example: return \App\Models\Notification::where('user_id', $this->user->id)->orderBy('created_at', 'desc')->paginate(15, ['*'], 'myNotificationsPage');
        return new EloquentCollection();
    }

    // --- Simplified Post Interactions (if displaying posts on this page) ---
    public function toggleLike(int $postId) {
        if (!Auth::check()) return;
        $post = Post::find($postId);
        if (!$post || $post->user_id !== $this->user->id) return;
        $authUser = Auth::user();
        if ($post->isLikedBy($authUser)) {
            $post->likes()->where('user_id', $authUser->id)->delete();
        } else {
            $post->likes()->create(['user_id' => $authUser->id]);
        }
    }
    // Add other post interaction methods if needed (toggleCommentsSection, addComment)

    public function render()
    {
        return view('livewire.profile.edit-form', [
            'myPostsList'         => ($this->activeTab === 'my_posts') ? $this->myPosts : new EloquentCollection(),
            'myFollowersList'     => ($this->activeTab === 'my_followers') ? $this->myFollowers : new EloquentCollection(),
            'myFollowingList'     => ($this->activeTab === 'my_following') ? $this->myFollowing : new EloquentCollection(),
            'myNotificationsList' => ($this->activeTab === 'notifications_settings' || $this->activeTab === 'view_notifications') ? $this->myNotifications : new EloquentCollection(),
        ]); // This component renders a full page
    }
}
