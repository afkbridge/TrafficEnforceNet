<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    /**
     * Display the user management page.
     */
    public function index()
    {
        if (auth()->user()->role_id !== 4) {
            abort(403);
        }

        $users = User::with('role')
            ->latest()
            ->paginate(10);

        $roles = Role::whereIn('id', [1, 2, 3])
            ->get();

        return view(
            'superadmin.users.index',
            compact('users', 'roles')
        );
    }


    /**
     * Create a new system account.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role_id !== 4) {
            abort(403);
        }

        $request->merge([
            '_form' => 'create',
        ]);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:255',
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
                'in:1,2,3',
            ],

            'account_status' => [
                'required',
                'in:Active,Inactive',
            ],
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'account_status' => $validated['account_status'],
        ]);

        return redirect()
            ->route('super-admin.users.index')
            ->with(
                'success',
                'User account created successfully.'
            );
    }


    /**
     * Update an existing account.
     */
    public function update(Request $request, User $user)
    {
        if (auth()->user()->role_id !== 4) {
            abort(403);
        }

        // Super Administrator accounts cannot be edited here.
        if ($user->role_id === 4) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $user->id,
            ],

            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
                'in:1,2,3',
            ],

            'account_status' => [
                'required',
                'in:Active,Inactive',
            ],
        ]);

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'role_id' => $validated['role_id'],
            'account_status' => $validated['account_status'],
        ]);

        return redirect()
            ->route('super-admin.users.index')
            ->with(
                'success',
                'User account updated successfully.'
            );
    }


    /**
     * Reset a user's password.
     */
    public function resetPassword(User $user)
    {
        if (auth()->user()->role_id !== 4) {
            abort(403);
        }

        // Super Administrator accounts cannot be reset here.
        if ($user->role_id === 4) {
            abort(403);
        }

        $temporaryPassword = Str::random(10);

        $user->update([
            'password' => Hash::make($temporaryPassword),
        ]);

        return redirect()
            ->route('super-admin.users.index')
            ->with(
                'success',
                'Password reset successfully. Temporary password: ' . $temporaryPassword
            );
    }


    /**
     * Enable or disable an account.
     */
    public function toggleStatus(User $user)
    {
        if (auth()->user()->role_id !== 4) {
            abort(403);
        }

        // Super Administrator accounts cannot be disabled.
        if ($user->role_id === 4) {
            abort(403);
        }

        $newStatus = strtolower($user->account_status) === 'active'
            ? 'Inactive'
            : 'Active';

        $user->update([
            'account_status' => $newStatus,
        ]);

        return redirect()
            ->route('super-admin.users.index')
            ->with(
                'success',
                'Account status updated successfully.'
            );
    }


    /**
     * Delete a user account.
     */
    public function destroy(User $user)
    {
        if (auth()->user()->role_id !== 4) {
            abort(403);
        }

        // Never allow deletion of a Super Administrator.
        if ($user->role_id === 4 || $user->id === auth()->id()) {
            abort(403);
        }

        $user->delete();

        return redirect()
            ->route('super-admin.users.index')
            ->with(
                'success',
                'User account deleted successfully.'
            );
    }
}
