<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Please enter your username.',
            'password.required' => 'Please enter your password.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $username = $this->string('username')->toString();
        $password = $this->string('password')->toString();

        /*
        |--------------------------------------------------------------------------
        | Find the user by username
        |--------------------------------------------------------------------------
        */
        $user = User::where('username', $username)->first();

        /*
        |--------------------------------------------------------------------------
        | Check username and password
        |--------------------------------------------------------------------------
        |
        | If the username does not exist OR the password is incorrect,
        | show one general login error.
        |
        */
        if (!$user || !Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => 'These credentials do not match our records.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check account status
        |--------------------------------------------------------------------------
        |
        | Only Active accounts are allowed to authenticate.
        |
        | This check happens BEFORE Auth::login(), so an Inactive
        | account never becomes authenticated.
        |
        */
        if (strtolower((string) $user->account_status) !== 'active') {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => 'Your account is currently inactive. Please contact the system administrator.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Credentials and account status are valid
        |--------------------------------------------------------------------------
        */
        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower(
                $this->string('username')->toString()
            ) . '|' . $this->ip()
        );
    }
}