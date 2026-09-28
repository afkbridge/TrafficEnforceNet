<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SAProfileController extends Controller
{
    /**
     * Display the Super Administrator's account settings.
     */
    public function edit(): View
    {
        return view('superadmin.users.profile', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the Super Administrator's account information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'username' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[A-Za-z0-9._-]+$/',
                    Rule::unique('users', 'username')->ignore($user->id),
                ],
            ],
            [
                'name.required' =>
                    'Please enter your name.',

                'username.required' =>
                    'Please enter your username.',

                'username.regex' =>
                    'Username may only contain letters, numbers, periods, dashes, and underscores.',

                'username.unique' =>
                    'This username is already being used by another account.',
            ]
        );

        $nameChanged = $user->name !== $validated['name'];
        $usernameChanged = $user->username !== $validated['username'];

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        if ($nameChanged || $usernameChanged) {

            $changes = [];

            if ($nameChanged) {
                $changes[] = 'name';
            }

            if ($usernameChanged) {
                $changes[] = 'username';
            }

            AuditLogger::log(
                'UPDATE PROFILE',
                'Super Administrator updated their account information: '
                . implode(' and ', $changes) . '.'
            );
        }

        return redirect()
            ->route('super-admin.profile')
            ->with(
                'success',
                'Your account information has been updated successfully.'
            );
    }

    /**
     * Change the Super Administrator's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate(
            [
                'current_password' => [
                    'required',
                    'current_password',
                ],

                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
            ],
            [
                'current_password.required' =>
                    'Please enter your current password.',

                'current_password.current_password' =>
                    'Your current password is incorrect.',

                'password.required' =>
                    'Please enter a new password.',

                'password.min' =>
                    'Your new password must be at least 8 characters.',

                'password.confirmed' =>
                    'The password confirmation does not match.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Update Password
        |--------------------------------------------------------------------------
        */

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        AuditLogger::log(
            'CHANGE PASSWORD',
            'Super Administrator changed their account password.'
        );

        /*
        |--------------------------------------------------------------------------
        | Logout After Password Change
        |--------------------------------------------------------------------------
        |
        | The current session is terminated so the Super Administrator must
        | authenticate again using the newly changed password.
        |
        */

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Your password has been changed successfully. Please log in using your new password.'
            );
    }
}