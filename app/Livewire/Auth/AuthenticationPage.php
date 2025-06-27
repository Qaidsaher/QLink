<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class AuthenticationPage extends Component
{
    // State Management
    // State Management
    public string $formState = 'login'; // 'login' or 'register'

    // Registration Form Properties
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Login Form Properties
    public string $loginIdentifier = ''; // Can be email or username
    public string $loginPassword = '';
    public bool $remember = false;

    // Username Live Validation
    public string $usernameStatus = 'idle'; // idle, checking, available, taken

    // Route Actions to set the initial form state
    public function loginView()
    {
        $this->formState = 'login';
    }

    public function registerView()
    {
        $this->formState = 'register';
    }

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
            'loginIdentifier' => ['required', 'string'],
            'loginPassword' => ['required', 'string'],
        ];
    }

    public function updatedUsername(string $value)
    {
        $this->usernameStatus = 'checking';
        $this->validateOnly('username');
        if ($this->getErrorBag()->has('username')) {
            $this->usernameStatus = 'taken';
        } else {
            $this->usernameStatus = 'available';
        }
    }

    public function switchForm(string $state)
    {
        $this->formState = $state;
        $this->resetErrorBag();
    }

    public function register()
    {
        $validatedData = $this->validate();

        $user = User::create([
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        Auth::login($user, true); // Log in and remember

        return redirect()->intended(route('feed'));
    }

    public function login()
    {
        $this->validate();

        $loginType = filter_var($this->loginIdentifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $this->loginIdentifier,
            'password' => $this->loginPassword,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            request()->session()->regenerate();
            return redirect()->intended(route('feed'));
        }

        $this->addError('loginIdentifier', 'The provided credentials do not match our records.');
    }

    public function redirectToProvider(string $provider)
    {
        return redirect()->route('google.login');
    }
    public function render()
    {
        return view('livewire.auth.authentication-page');
    }
}
