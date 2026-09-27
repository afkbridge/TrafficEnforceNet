<?php

namespace App\Http\Controllers\Enforcer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the enforcer profile page.
     */
    public function show()
    {
        return view('enforcer.profile', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Update the enforcer's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'current_password.required' => 'Please enter your current password.',
            'password.required' => 'Please enter a new password.',
            'password.min' => 'The new password must be at least 8 characters.',
            'password.confirmed' => 'The new password confirmation does not match.',
        ]);

        // Get the currently authenticated enforcer.
        $user = $request->user();

        // Check if the entered current password is correct.
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors([
                    'current_password' => 'Your current password is incorrect.'
                ])
                ->withInput();
        }

        // Save the new hashed password.
        $user->password = Hash::make($request->password);

        $user->save();

        // Return to the profile page with a success message.
        return back()
            ->with('password_success', 'Your password has been changed successfully.');
    }
}