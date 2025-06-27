<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Auth as AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class LoginRegisterModal extends Component
{
    // State Management
    public bool $isOpen = false;
    public string $formState = 'login'; // 'login' or 'register'

    // Registration Form Properties
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Login Form Properties
    public string $loginEmail = '';
    public string $loginPassword = '';
    public bool $remember = false;

    // Username Live Validation
    public string $usernameStatus = 'idle'; // idle, checking, available, taken

    // Listen for events to open the modal from other components
    protected $listeners = ['openModal' => 'open'];

    /**
     * Re-opens the modal if a validation error occurred on a previous submission.
     */
    public function mount()
    {
        if (session('show_login_register_modal')) {
            $this->isOpen = true;
            $this->formState = session('form_state', 'login');
        }
    }

    /**
     * Rules are defined as a method for better organization.
     */
    protected function rules()
    {
        if ($this->formState === 'register') {
            return [
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'lowercase', 'max:255', 'alpha_dash', 'unique:users,username'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'confirmed', Password::defaults()],
            ];
        }

        return [
            'loginEmail' => ['required', 'string', 'email'],
            'loginPassword' => ['required', 'string'],
        ];
    }

    /**
     * Real-time validation for the username field.
     * This is triggered as the user types.
     */
    public function updatedUsername(string $value)
    {
        $this->usernameStatus = 'checking';
        $this->validateOnly('username');

        // After validation passes, we can be sure the format is correct.
        // The 'unique' rule in validateOnly will handle the existence check.
        // If an error bag for 'username' exists, it's taken. Otherwise, it's available.
        if ($this->getErrorBag()->has('username')) {
            $this->usernameStatus = 'taken';
        } else {
            $this->usernameStatus = 'available';
        }
    }

    /**
     * Open the modal and set its initial state.
     */
    public function open(string $initialState = 'login')
    {
        $this->formState = $initialState;
        $this->isOpen = true;
    }

    /**
     * Close the modal and reset validation.
     */
    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
        $this->reset(); // Resets all public properties
    }

    /**
     * Switch between login and register forms.
     */
    public function switchForm(string $state)
    {
        $this->formState = $state;
        $this->usernameStatus = 'idle'; // Reset username status when switching
        $this->resetValidation();
    }

    /**
     * Handle the registration form submission.
     */
    public function register()
    {
        $validatedData = $this->validate($this->rules());

        $user = User::create([
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        AuthService::login($user);
        // Auth::user()->notify(instance: new UserNotification(action: 'register', extra: 'you register successfully to our platform'));
        $this->closeModal();

        return redirect()->route('feed');
    }

    /**
     * Handle the login form submission.
     */
    public function login()
    {
        $credentials = $this->validate([
            'loginEmail' => ['required', 'email'],
            'loginPassword' => ['required'],
        ]);

        if (AuthService::attempt(['email' => $this->loginEmail, 'password' => $this->loginPassword], $this->remember)) {
            request()->session()->regenerate();
            // Auth::user()->notify(instance: new UserNotification(action: 'login', extra: 'you login successfully to your account'));

            $this->closeModal();
            return redirect()->intended(route('feed'));
        }

        // Add a custom error if authentication fails.
        $this->addError('loginEmail', 'The provided credentials do not match our records.');
    }


    public function render()
    {
        return view('livewire.auth.login-register-modal');
    }
}
