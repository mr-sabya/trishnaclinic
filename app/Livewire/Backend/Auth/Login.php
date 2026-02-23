<?php

namespace App\Livewire\Backend\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public $login_identifier = ''; // This will hold either Email or Phone
    public $password = '';
    public $remember = false;

    protected function rules()
    {
        return [
            'login_identifier' => 'required|string',
            'password' => 'required',
        ];
    }

    public function authenticate()
    {
        $this->validate();

        // Check if input is a valid email, otherwise treat as phone
        $fieldType = filter_var($this->login_identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Attempt login (also checking if the account is active)
        $credentials = [
            $fieldType => $this->login_identifier,
            'password' => $this->password,
            'is_active' => true // Only allow active users
        ];

        if (!Auth::attempt($credentials, $this->remember)) {
            throw ValidationException::withMessages([
                'login_identifier' => __('auth.failed'),
            ]);
        }

        session()->regenerate();

        // Role-based redirection logic
        $user = Auth::user();

        return match ($user->role->value) {
            'super_admin', 'admin' => redirect()->route('admin.home'),
            'receptionist'         => redirect()->route('reception.dashboard'),
            'accountant'           => redirect()->route('accounting.dashboard'),
            'patient'              => redirect()->route('patient.portal'),
            default                => redirect('/dashboard'),
        };
    }

    public function render()
    {
        return view('livewire.backend.auth.login');
    }
}
