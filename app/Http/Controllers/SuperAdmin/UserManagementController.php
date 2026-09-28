<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Display all manageable system users.
     */
    public function index()
    {
        $users = User::with('role')
            ->latest()
            ->paginate(10);

        $roles = Role::where('id', '!=', 4)
            ->orderBy('id')
            ->get();

        return view('superadmin.users.index', compact('users', 'roles'));
    }

    /**
     * Create a new system user.
     */
    public function store(Request $request): RedirectResponse
    {
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
                    'max:100',
                    'regex:/^[A-Za-z0-9._-]+$/',
                    'unique:users,username',
                ],

                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],

                'role_id' => [
                    'required',
                    'integer',
                    'exists:roles,id',
                    Rule::notIn([4]),
                ],

                'account_status' => [
                    'required',
                    Rule::in(['Active', 'Inactive']),
                ],
            ],
            [
                'username.regex' =>
                    'Username may only contain letters, numbers, periods, dashes, and underscores.',

                'username.unique' =>
                    'That username is already being used.',

                'password.min' =>
                    'Password must be at least 8 characters.',

                'password.confirmed' =>
                    'Password confirmation does not match.',

                'role_id.not_in' =>
                    'Super Administrator accounts cannot be created here.',
            ]
        );

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'account_status' => $validated['account_status'],
        ]);

        return redirect()
            ->route('super-admin.users')
            ->with('success', 'User account created successfully.');
    }

    /**
     * Update an existing system user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent modification of Super Administrator accounts
        |--------------------------------------------------------------------------
        */
        if ((int) $user->role_id === 4) {
            return redirect()
                ->route('super-admin.users')
                ->with(
                    'error',
                    'Super Administrator accounts cannot be modified here.'
                );
        }

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
                    'max:100',
                    'regex:/^[A-Za-z0-9._-]+$/',
                    Rule::unique('users', 'username')->ignore($user->id),
                ],

                'role_id' => [
                    'required',
                    'integer',
                    'exists:roles,id',
                    Rule::notIn([4]),
                ],

                'account_status' => [
                    'required',
                    Rule::in(['Active', 'Inactive']),
                ],
            ],
            [
                'username.regex' =>
                    'Username may only contain letters, numbers, periods, dashes, and underscores.',

                'username.unique' =>
                    'That username is already being used.',

                'role_id.not_in' =>
                    'A Super Administrator role cannot be assigned here.',
            ]
        );

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'role_id' => $validated['role_id'],
            'account_status' => $validated['account_status'],
        ]);

        return redirect()
            ->route('super-admin.users')
            ->with('success', 'User account updated successfully.');
    }

    /**
     * Generate and assign a new temporary password.
     */
    public function resetPassword(
        Request $request,
        User $user
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Prevent password reset of Super Administrator accounts
        |--------------------------------------------------------------------------
        */
        if ((int) $user->role_id === 4) {
            return redirect()
                ->route('super-admin.users')
                ->with(
                    'error',
                    'Super Administrator passwords cannot be reset here.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Generate temporary password
        |--------------------------------------------------------------------------
        */
        $temporaryPassword = $this->generateTemporaryPassword();

        $user->update([
            'password' => Hash::make($temporaryPassword),
        ]);

        return redirect()
            ->route('super-admin.users')
            ->with(
                'success',
                "Password reset successfully for {$user->name}. Temporary password: {$temporaryPassword}"
            );
    }

    /**
     * Enable or disable an account.
     */
    public function toggleStatus(
        Request $request,
        User $user
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Prevent modification of Super Administrator accounts
        |--------------------------------------------------------------------------
        */
        if ((int) $user->role_id === 4) {
            return redirect()
                ->route('super-admin.users')
                ->with(
                    'error',
                    'Super Administrator accounts cannot be disabled.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent the currently authenticated Super Administrator
        | from disabling their own account.
        |--------------------------------------------------------------------------
        */
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('super-admin.users')
                ->with(
                    'error',
                    'You cannot disable your own account.'
                );
        }

        $isActive = strtolower(
            (string) $user->account_status
        ) === 'active';

        $user->update([
            'account_status' => $isActive
                ? 'Inactive'
                : 'Active',
        ]);

        $message = $isActive
            ? "Account for {$user->name} has been disabled."
            : "Account for {$user->name} has been enabled.";

        return redirect()
            ->route('super-admin.users')
            ->with('success', $message);
    }

    /**
     * Permanently delete a system user.
     */
    public function destroy(
        Request $request,
        User $user
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Prevent deletion of Super Administrator accounts
        |--------------------------------------------------------------------------
        */
        if ((int) $user->role_id === 4) {
            return redirect()
                ->route('super-admin.users')
                ->with(
                    'error',
                    'Super Administrator accounts cannot be deleted.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent self-deletion
        |--------------------------------------------------------------------------
        */
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('super-admin.users')
                ->with(
                    'error',
                    'You cannot delete your own account.'
                );
        }

        $userName = $user->name;

        $user->delete();

        return redirect()
            ->route('super-admin.users')
            ->with(
                'success',
                "Account for {$userName} was deleted successfully."
            );
    }

    /**
     * Generate a readable temporary password.
     */
    private function generateTemporaryPassword(): string
    {
        return Str::random(12);
    }
}