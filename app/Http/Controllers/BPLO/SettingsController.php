<?php

namespace App\Http\Controllers\BPLO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Display BPLO settings.
     */
    public function index()
    {
        $user = Auth::user();

        return view('bplo.settings.index', compact('user'));
    }

    /**
     * Update the logged-in BPLO user's account information.
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->save();

        return redirect()
            ->route('bplo.settings')
            ->with('success', 'Account settings updated successfully.');
    }

    /**
     * Update the logged-in BPLO user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors([
                    'current_password' => 'The current password is incorrect.',
                ])
                ->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
            ->route('bplo.settings')
            ->with('success', 'Password changed successfully.');
    }
}